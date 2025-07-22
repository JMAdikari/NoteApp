<?php
namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    // Get the authenticated user's profile ,
    // User profile operations and account management
     
    public function profile(Request $request)
    {
        try {
            $user = $request->user(); // gets user from the API token

            if (!$user) {
                return response()->json(['message' => 'User not found'], 404);
            }

            // Get user with note count
            // User is authenticated, proceed with operation
            $user->loadCount('notes');
            
            return response()->json([
                'user' => [
                    'id' => $user->id,
                    'name' => $user->name,
                    'email' => $user->email,
                    'notes_count' => $user->notes_count,
                    'created_at' => $user->created_at,
                    'updated_at' => $user->updated_at,
                ]
            ], 200);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Error fetching profile', 'error' => $e->getMessage()], 500);
        }
    }


      // Update the authenticated user's profile

    public function updateProfile(Request $request)
    {
        try {
            $user = $request->user(); // Get authenticated user
            
            if (!$user) {
                return response()->json(['message' => 'User not found'], 404);
            }

            $validator = Validator::make($request->all(), [
                'name' => 'required|string|max:255',
                'email' => 'required|string|email|max:255|unique:users,email,' . $user->id,
            ]);

            if ($validator->fails()) {
                return response()->json(['errors' => $validator->errors()], 422);
            }

            $user->update([
                'name' => $request->name,
                'email' => $request->email,
            ]);

            return response()->json([
                'message' => 'Profile updated successfully',
                'user' => [
                    'id' => $user->id,
                    'name' => $user->name,
                    'email' => $user->email,
                    'created_at' => $user->created_at,
                    'updated_at' => $user->updated_at,
                ]
            ], 200);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Error updating profile', 'error' => $e->getMessage()], 500);
        }
    }


     // Change the authenticated user's password

    public function changePassword(Request $request)
    {
        try {
            $user = $request->user();
            
            if (!$user) {
                return response()->json(['message' => 'User not found'], 404);
            }

            $validator = Validator::make($request->all(), [
                'current_password' => 'required|string',
                'new_password' => 'required|string|min:8|confirmed',
            ]);

            if ($validator->fails()) {
                return response()->json(['errors' => $validator->errors()], 422);
            }

            // Check if current password is correct
            if (!Hash::check($request->current_password, $user->password)) {
                return response()->json(['message' => 'Current password is incorrect'], 400);
            }

            // Update password
            $user->update([
                'password' => Hash::make($request->new_password),
            ]);

            return response()->json([
                'message' => 'Password changed successfully'
            ], 200);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Error changing password', 'error' => $e->getMessage()], 500);
        }
    }

    // Delete the authenticated user's account

    public function deleteAccount(Request $request)
    {
        try {
            $user = $request->user();
            
            if (!$user) {
                return response()->json(['message' => 'User not found'], 404);
            }

            $validator = Validator::make($request->all(), [
                'password' => 'required|string',
            ]);

            if ($validator->fails()) {
                return response()->json(['errors' => $validator->errors()], 422);
            }

            // Check if password is correct
            if (!Hash::check($request->password, $user->password)) {
                return response()->json(['message' => 'Password is incorrect'], 400);
            }

            // Delete all user's notes first
            $user->notes()->delete();
            
            // Delete all user's tokens
            $user->tokens()->delete();
            
            // Delete the user
            $user->delete();

            return response()->json([
                'message' => 'Account deleted successfully'
            ], 200);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Error deleting account', 'error' => $e->getMessage()], 500);
        }
    }
}
