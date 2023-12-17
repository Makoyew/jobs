@extends('base')

@section('content')
<div class="text-white">
    <header class="p-4 shadow-lg" style="
        z-index: 1;
        position: fixed;
        width: 100%;
        background-color: rgb(29, 29, 29);">
        <div class="d-flex justify-content-between">
            <div class="">
                <h1 style="font-family: 'Pacifico', cursive; text-shadow: 0 0 10px white;"><i class="fa-solid fa-seedling"></i> Fruity Loops store</h1>
            </div>
            <div class="mt-1">
                <a class="btn text-white" href="/dashboard"><i class="fa-solid fa-headphones"></i> Plugins</a>
                @role('admin')
                <a class="btn text-white" href="/logs"><i class="fa-solid fa-envelope"></i> Logs</a>
                @endrole

                <button class="text-white rounded-lg pe-4 ps-4 text-danger btn" style="background-color: transparent; font-size: 20px;" id="logoutButton" data-toggle="modal" data-target="#confirmLogoutModal"><i class="fa-solid fa-right-from-bracket"></i> {{ Auth::user()->name }}</button>
            </div>
        </div>
    </header>

    <div>
        <div class="p-5">
            <div style="margin-top: 100px;">
                <h1 class="d-flex justify-content-between"> Plugins
                    @role('admin')
                    <button style="text-shadow: 0 0 10px white;" type="button" class="btn text-white" data-toggle="modal" data-target="#exampleModal">
                        <i class="fa fa-plus"></i> Add Plugins
                    </button>
                    @endrole

                </h1>
                <br>
                <div class="d-flex flex-wrap justify-content-between">
                    @foreach ($plugins as $plugin)
                        <div
                        class="p-3 rounded-lg shadow-lg"
                        style="margin-bottom: 125px;
                        width: 500px;
                        background-repeat: no-repeat;
                        background-size: cover;
                        background-position: center;
                        background-image: url(https://assets-global.website-files.com/62d691a88df4876c34575a08/6401d36f2ecfa94ef493eefc_10-best-vst-plugins-for-music-production.webp)
                        ">
                            <h5><i class="fa-solid fa-signature"></i> Name: {{$plugin->name}}</h5> <br> <br>
                            <h5><i class="fa-solid fa-tags"></i> Price: {{$plugin->price}}</h5> <br> <br>
                            <h5><i class="fa-solid fa-shield-halved"></i> Supported Daws: <br> <h6 class="">{{$plugin->daws}}</h6></h5> <br> <br>
                            <h5><i class="fa-solid fa-font"></i> Description: </h5>
                            <textarea
                            disabled
                            class="text-white border-0 shadow-lg rounded-lg p-2"
                            style="background-color: rgba(0, 0, 0, 0.599); resize: none;"
                            name="" id="" cols="59" rows="5">{{$plugin->description}}</textarea> <br> <br>
                            <div class="text-right flex">
                                <form action="{{ route('plugin.download', $plugin) }}" method="POST">
                                    @csrf <!-- This adds the CSRF token -->
                                    @role('user')
                                        <button type="submit" style="text-shadow: 0 0 10px white;" class="btn text-white"><i class="fa fa-edit"></i> Download</button>
                                    @endrole

                                    @role('admin')
                                    <button type="button" style="text-shadow: 0 0 10px white;" class="btn text-white" data-toggle="modal" data-target="#editModal-{{ $plugin->id }}"><i class="fa fa-edit"></i> Edit</button>
                                    <button type="button" style="text-shadow: 0 0 10px white;" class="btn text-white" data-toggle="modal" data-target="#deleteModal-{{ $plugin->id }}" data-plugin-id="{{ $plugin->id }}"><i class="fa fa-trash"></i> Delete</button>
                                    @endrole

                                </form>



                            </div>
                        </div>
                        <!-- Edit Modal -->
                        <div id="editModal-{{ $plugin->id }}" class="modal fade" style="margin-top: 100px" tabindex="-1" role="dialog" aria-labelledby="editModalLabel" aria-hidden="true">
                            <div class="modal-dialog text-white" role="document">
                                <div class="modal-content" style="background-color: rgb(15, 15, 15)">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="editModalLabel">Edit Plugin</h5>
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                            <span aria-hidden="true">&times;</span>
                                        </button>
                                    </div>
                                    <div class="modal-body">
                                        <form id="editForm-{{ $plugin->id }}" method="POST" action="{{ route('plugins.update', $plugin) }}">
                                            @csrf
                                            @method('PATCH')

                                            <!-- Add form fields for editing the plugin's data here -->
                                            <div class="form-group">
                                                <label for="name">Name:</label>
                                                <input type="text" class="form-control bg-transparent text-white" id="name" name="name" value="{{ $plugin->name }}">
                                            </div>

                                            <div class="form-group">
                                                <label for="description">Description:</label>
                                                <textarea class="form-control bg-transparent text-white" id="description" name="description" rows="5">{{ $plugin->description }}</textarea>
                                            </div>

                                            <div class="form-group">
                                                <label for="price">Price:</label>
                                                <input type="text" class="form-control bg-transparent text-white" id="price" name="price" value="{{ $plugin->price }}">
                                            </div>

                                            <div class="">

                                                <div>
                                                    <label class="form-label bg-transparent text-white"><i class="fa-solid fa-shield-halved"></i> Select Supported Daws:</label>
                                                </div>
                                            </label>
                                            <div style="display: flex; flex-wrap: wrap;">
                                                <div class="form-check m-3">
                                                    <input class="form-check-input" type="checkbox" name="software[]" value="FL Studio" id="flstudio" onchange="updateSelectedSoftware()"
                                                    @if(in_array('FL Studio', explode(', ', $plugin->daws))) checked @endif>
                                                    <label class="form-check-label" for="flstudio">FL Studio</label>
                                                </div>

                                                <div class="form-check m-3">
                                                    <input class="form-check-input" type="checkbox" name="software[]" value="Pro Tools" id="protools" onchange="updateSelectedSoftware()"
                                                    @if(in_array('Pro Tools', explode(', ', $plugin->daws))) checked @endif>
                                                    <label class="form-check-label" for="protools">Pro Tools</label>
                                                </div>

                                                <div class="form-check m-3">
                                                    <input class="form-check-input" type="checkbox" name="software[]" value="Ableton Live" id="ableton" onchange="updateSelectedSoftware()"
                                                    @if(in_array('Ableton Live', explode(', ', $plugin->daws))) checked @endif>
                                                    <label class="form-check-label" for="ableton">Ableton Live</label>
                                                </div>

                                                <div class="form-check m-3">
                                                    <input class="form-check-input" type="checkbox" name="software[]" value="Logic Pro" id="logicpro" onchange="updateSelectedSoftware()"
                                                    @if(in_array('Logic Pro', explode(', ', $plugin->daws))) checked @endif>
                                                    <label class="form-check-label" for="logicpro">Logic Pro</label>
                                                </div>

                                                <div class="form-check m-3">
                                                    <input class="form-check-input" type="checkbox" name="software[]" value="Audacity" id="audacity" onchange="updateSelectedSoftware()"
                                                    @if(in_array('Audacity', explode(', ', $plugin->daws))) checked @endif>
                                                    <label class="form-check-label" for="audacity">Audacity</label>
                                                </div>

                                                <div class="form-check m-3">
                                                    <input class="form-check-input" type="checkbox" name="software[]" value="Cubase" id="cubase" onchange="updateSelectedSoftware()"
                                                    @if(in_array('Cubase', explode(', ', $plugin->daws))) checked @endif>
                                                    <label class="form-check-label" for="cubase">Cubase</label>
                                                </div>

                                                <div class="form-check m-3">
                                                    <input class="form-check-input" type="checkbox" name="software[]" value="Reaper" id="reaper" onchange="updateSelectedSoftware()"
                                                    @if(in_array('Reaper', explode(', ', $plugin->daws))) checked @endif>
                                                    <label class="form-check-label" for="reaper">Reaper</label>
                                                </div>
                                            </div>

                                            </div>
                                        </form>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary" data-dismiss="modal"><i class="fa fa-cancel"></i> Cancel</button>
                                        <button type="submit" form="editForm-{{ $plugin->id }}" class="btn btn-success"><i class="fa fa-save"></i> Save Changes</button>
                                    </div>
                                </div>
                            </div>
                        </div>




                        <!-- Delete Modal -->
                        <div id="deleteModal-{{ $plugin->id }}" class="modal fade" style="margin-top: 300px" tabindex="-1" role="dialog" aria-labelledby="deleteModalLabel" aria-hidden="true">
                            <div class="modal-dialog text-white" role="document">
                                <div class="modal-content" style="background-color: rgb(15, 15, 15)">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="deleteModalLabel">Confirm Deletion</h5>
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                            <span aria-hidden="true">&times;</span>
                                        </button>
                                    </div>
                                    <div class="modal-body">
                                        Are you sure you want to delete this plugin?
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                                        <form id="deleteForm-{{ $plugin->id }}" method="POST" action="{{ route('plugins.destroy', $plugin) }}">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-danger">Delete</button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>

                    @endforeach
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Confirm Logout -->
<div class="modal fade" style="margin-top: 300px" id="confirmLogoutModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog text-white" role="document">
      <div class="modal-content" style="background-color: rgb(15, 15, 15)">
        <div class="modal-header">
          <h5 class="modal-title" id="exampleModalLabel">Confirm Logout</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          Are you sure you want to logout?
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
          <form action="{{ route('logout') }}" method="POST">
            @csrf
            <button type="submit" class="btn btn-danger">Confirm Logout</button>
          </form>
        </div>
      </div>
    </div>
  </div>

<!-- Modal -->
<div  class="modal fade" style="margin-top: 100px" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">

        <div class="modal-content text-white" style="background-color: rgb(15, 15, 15)">
            <div class="modal-header">
            <h5 class="modal-title" id="exampleModalLabel"><b>Create Plugin</b></h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
            </div>

            <form method="POST" action="{{ route('plugins.store') }}">
                @csrf
            <div class="modal-body">
                <div class="form-group">
                    <div class="form-group">
                        <label for="name">Name:</label>
                        <input type="text" name="name" id="name" class="form-control bg-transparent text-white" required>
                    </div>
                    <div class="form-group">
                        <label for="description">Description:</label>
                        <textarea name="description" id="description" class="form-control bg-transparent text-white" required></textarea>
                    </div>
                    <div class="form-group">
                        <label for="price">Price:</label>
                        <input type="number" name="price" id="price" class="form-control bg-transparent text-white" required>
                    </div>


                    <div class="">
                        <label class="form-label bg-transparent text-white"><i class="fa-solid fa-shield-halved"></i> Select Supported Daws:</label>
                    <div style="display: flex; flex-wrap: wrap;">
                        <div class="form-check m-3">
                            <input class="form-check-input" type="checkbox" name="software[]" value="FL Studio" id="flstudio" onchange="updateSelectedSoftware()">
                            <label class="form-check-label" for="flstudio">FL Studio</label>
                        </div>

                        <div class="form-check m-3">
                            <input class="form-check-input" type="checkbox" name="software[]" value="Pro Tools" id="protools" onchange="updateSelectedSoftware()">
                            <label class="form-check-label" for="protools">Pro Tools</label>
                        </div>

                        <div class="form-check m-3">
                            <input class="form-check-input" type="checkbox" name="software[]" value="Ableton Live" id="ableton" onchange="updateSelectedSoftware()">
                            <label class="form-check-label" for="ableton">Ableton Live</label>
                        </div>

                        <div class="form-check m-3">
                            <input class="form-check-input" type="checkbox" name="software[]" value="Logic Pro" id="logicpro" onchange="updateSelectedSoftware()">
                            <label class="form-check-label" for="logicpro">Logic Pro</label>
                        </div>

                        <div class="form-check m-3">
                            <input class="form-check-input" type="checkbox" name="software[]" value="Audacity" id="audacity" onchange="updateSelectedSoftware()">
                            <label class="form-check-label" for="audacity">Audacity</label>
                        </div>

                        <div class="form-check m-3">
                            <input class="form-check-input" type="checkbox" name="software[]" value="Cubase" id="cubase" onchange="updateSelectedSoftware()">
                            <label class="form-check-label" for="cubase">Cubase</label>
                        </div>

                        <div class="form-check m-3">
                            <input class="form-check-input" type="checkbox" name="software[]" value="Reaper" id="reaper" onchange="updateSelectedSoftware()">
                            <label class="form-check-label" for="reaper">Reaper</label>
                        </div>
                    </div>

                    </div>


                </div>
            </div>

            <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            <button type="submit" class="btn btn-primary">Save changes</button>

            </div>
        </div>
        </div>
    </form>
</div>

<style>
    .software-checkboxes {
        display: flex;
        flex-direction: column;
        margin: 20px;
    }
    .form-check {
        display: flex;
        align-items: center;
    }
</style>
<script>
   function updateSelectedSoftware() {
        const checkboxes = document.querySelectorAll('input[name="software"]');
        const selectedSoftware = [];

        checkboxes.forEach(checkbox => {
            if (checkbox.checked) {
                selectedSoftware.push(checkbox.value);
            }
        });

        const selectedSoftwareInput = document.getElementById('daws');
        selectedSoftwareInput.value = selectedSoftware.join(', '); // or use any other delimiter you prefer
    }
</script>

@endsection
@auth

@endauth

