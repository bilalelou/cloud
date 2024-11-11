<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;


class testController extends Controller
{
    function index()
    {
         Auth::loginUsingId(1);
         $user = auth()->user();


      //    $response = Http::withHeaders([
      //     "apikey" => "5zYeg6RngxrlsTNJtzqp3ta2kdS3Fv96",
      // ])->withoutVerifying()->get("https://api.idcloudhost.com/v1/config/locations");

      // $data = $response->json();
      // dump($data);
        //  dd(auth()->user()->toArray());
       return redirect("/CloudServersProviders");
          

    
   
        // $user = new User();

        // // Set user details
        // $user->name = "test";
        // $user->email = "test@eimpact.com";
        // $user->password = Hash::make('password'); 

        // // Step 2: Save the user to the database
        // // $user->save();

        // $admin = Role::Create(['name'=>'new_role']);
        //  $permissions = Permission::all();
        //  $user->syncPermissions($permissions);
        // $admin->givePermissionTo($permissions);


        // if ($user) {
        //     // Retrieve all permissions directly using the permissions relationship
        //     $userPermissions = $user->permissions; // This returns a collection of permission objects
        
        //     // Optionally, convert to an array of permission names for easier reading
        //     $permissionsArray = $userPermissions->pluck('name')->toArray();
        
        //     // Display or return the permissions
        //     dump($permissionsArray); // For debugging
        
        //     // Example JSON response (if in a controller method)
        //     return response()->json([
        //         'message' => 'Permissions retrieved successfully.',
        //         'permissions' => $permissionsArray
        //     ]);
        // } else {
        //     return response()->json([
        //         'message' => 'User not authenticated.',
        //     ], 401);
        // }
        
        
        
    //     if ($user) {
    //         // Retrieve the user's roles
            // $userRole = $user->getRoleNames(); // Gets a collection of role names

    //         // Dump the roles for debugging
    //         dump("User roles:");
    //         dump($userRole->toArray()); // Convert the roles to an array and dump it

    //         return response()->json([
    //             'message' => 'User roles have been dumped successfully.',
    //             'roles' => $userRole
    //         ]);
    //     } else {
    //         return response()->json([
    //             'message' => 'User not found.',
    //         ], 404);
    //     }
    // }

}
}  
 