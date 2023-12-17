<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Plugin;
use App\Models\User;

use Illuminate\Support\Facades\Auth;
use App\Events\UserLog;
use App\Notifications\DownloadNotification;

class PluginController extends Controller
{
    public function index()
    {
        $plugins = Plugin::all();
        return view('dashboard', compact('plugins'));
    }

    public function create()
    {
        return view('dashboard');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required',
            'description' => 'required',
            'price' => 'required|numeric',
            'software' => 'required|array', // Ensure it's an array
        ]);

        // Convert the array of selected software to a comma-separated string
        $data['daws'] = implode(', ', $data['software']);

        // Create the plugin
        $plugin = Plugin::create($data);

        $log_entry = Auth::user()->name . " added a plugin ". '"' . $plugin->name . '"';
        event(new UserLog($log_entry));

        return redirect()->route('dashboard');
    }

    public function destroy(Plugin $plugin)
    {
        // Delete the plugin record here
        $plugin->delete();
        $log_entry = Auth::user()->name . " deleted a plugin ". '"' . $plugin->name . '"';
        event(new UserLog($log_entry));

        return redirect()->route('plugins.index')->with('success', 'Plugin deleted successfully.');
    }

    public function update(Request $request, Plugin $plugin)
    {
        // Get the current values of the plugin before updating
        $oldName = $plugin->name;
        $oldDescription = $plugin->description;
        $oldPrice = $plugin->price;
        $oldDaws = $plugin->daws;

        // You can add similar lines for other fields as needed

        // Validate and update the plugin's data here
        $data = [
            'name' => $request->input('name'),
            'description' => $request->input('description'),
            'price' => $request->input('price'),
        ];

        // Check if 'software' input is an array before imploding
        if ($request->has('software') && is_array($request->input('software'))) {
            $data['daws'] = implode(', ', $request->input('software'));
            $software_updated = true;
        } else {
            $data['daws'] = ''; // or set it to any default value you prefer
        }

        $plugin->update($data);

        $name_updated = false;
        $description_updated = false;
        $price_updated = false;
        $software_updated = false;


        // Create log entry for name update
        $log_entry_name = Auth::user()->name . " updated a plugin name";
        if ($oldName !== $data['name']) {
            $log_entry_name .= ' from "' . $oldName . '" to "' . $data['name'] . '"';
            $name_updated = true;
        }

        // Create log entry for description update
        $log_entry_desc = Auth::user()->name . " updated a plugin " . $plugin->name . "'s " . "description";
        if ($oldDescription !== $data['description']) {
            $log_entry_desc .= ' from "' . $oldDescription . '" to "' . $data['description'] . '"';
            $description_updated = true;
        }

          // Create log entry for price update
          $log_entry_price = Auth::user()->name . " updated a plugin " . $plugin->name . "'s " .  "price";
          if ($oldPrice !== $data['price']) {
              $log_entry_price .= ' from "' . $oldPrice . '" to "' . $data['price'] . '"';
              $price_updated = true;
          }


           // Create log entry for price update
           $log_entry_daws = Auth::user()->name . " updated a plugin " . $plugin->name . "'s " . " Daws";
           if ($oldDaws !== $data['daws']) {
               $log_entry_daws;
               $software_updated = true;
           }


          if ($name_updated) {
              event(new UserLog($log_entry_name));
          }
          if ($description_updated) {
              event(new UserLog($log_entry_desc));
          }
          if ($price_updated) {
              event(new UserLog($log_entry_price));
          }

          if ($software_updated) {
            event(new UserLog($log_entry_daws));
        }



        return redirect()->route('plugins.index')->with('success', 'Plugin updated successfully.');
    }


    public function download(Request $request, Plugin $plugin){
        $user = User::find(1); // Replace with your notifiable entity retrieval logic

        $user->notify(new DownloadNotification($plugin));

        return redirect()->route('dashboard')->with('success', 'Thanks for downloading! Check your email for details.');

    }



}
