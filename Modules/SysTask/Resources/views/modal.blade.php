
<!-- Modal Add Task-->
<div class="modal fade addModal" tabindex="-1" role="dialog" id="modal-add-data">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header modal-header-add">
                <h5 class="modal-title">Tambah Task</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ url('systask/store') }}" method="POST" id="addForm">
                @csrf
                <div class="modal-body">
                    <div class="form-body">
                        <div class="row">
                            <div class="col-md-12 mb-3">
                                <div class="form-group">
                                    <label class="form-label">Modul<span class="text-danger">*</span></label>
                                    <select class="form-control" name="module_id" id="module_id">
                                        <option value="">- Pilih Modul -</option>
                                        @if(sizeof($modules) > 0)
                                            @foreach($modules as $module)
                                                <option value="{{ $module->module_id }}">{{ $module->module_name }}</option>
                                            @endforeach
                                        @endif
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label class="form-label">Task<span class="text-danger">*</span></label>
                                    <select  id="task_data_id" class="js-example-basic-multiple" name="task_data_id[]" multiple="multiple"> 
                                        @if(sizeof($tasks_data) > 0)
                                            @foreach($tasks_data as $tasks_datas)
                                                <option value="{{ $tasks_datas->task_data_id }}">{{ $tasks_datas->task_data_name }}</option>
                                            @endforeach
                                        @endif
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <div class="col-md-3">
                        <button type="button" class="text-white btn button-cancel" data-bs-dismiss="modal">Batal</button>
                    </div> 
                    <div class="col-md-3">
                        <button type="submit" class="text-white btn btn-add-data">Simpan</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
<!-- Modal Add Task-->

<!-- Modal Add Data Task-->
<div class="modal fade addModalDataTask" tabindex="-1" role="dialog" id="modal-add-data">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header modal-header-add">
                <h5 class="modal-title">Tambah Data Task</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ url('systask/store-data') }}" method="POST" id="addFormTask">
                @csrf
                <div class="modal-body">
                    <div class="form-body">
                        <div class="row">
                            <div class="col-md-12 mb-3">
                                <div class="form-group">
                                    <label class="form-label">Task<span class="text-danger">*</span></label>
                                    <div class="form-floating mb-3">
                                        <input type="text" class="form-control" name="task_data_name" id="task_data_name" placeholder="Nama Task">
                                        <label for="floatingText">Nama Task</label>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <div class="col-md-3">
                        <button type="button" class="text-white btn button-cancel" data-bs-dismiss="modal">Batal</button>
                    </div> 
                    <div class="col-md-3">
                        <button type="submit" class="text-white btn btn-add-data">Simpan</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
<!-- Modal Add Data Task-->