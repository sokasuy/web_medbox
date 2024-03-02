<div class="modal-header">
    <h5 class="modal-title" id="modal_containerlabel"><i class="fas fa-id-card"></i> Customer Data</h5>
    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
        <span aria-hidden="true">&times;</span>
    </button>
</div>
<div class="modal-body">
    <div class="row">
        <!-- left column -->
        <div class="col-md-7">
            <!-- general form elements -->
            <!-- form start -->
            <form enctype="multipart/form-data" role="form" method="POST">
                @csrf
                @method('PUT')
                <div class="card-body">
                    {{-- ENTITI --}}
                    <input type="hidden" class="form-control" id="entiti" name="entiti" placeholder="-"
                        value="{{ $data->entiti }}" readonly>
                    {{-- KODE KONTAK --}}
                    <div class="form-group">
                        <label for="kodekontak">Kode Kontak</label>
                        <div class="input-group mb-3">
                            <input id="kodekontak" type="text" class="form-control" name="kodekontak"
                                placeholder="Kode Kontak" value="{{ $data->kodekontak }}" readonly required
                                autocomplete="kodekontak" autofocus>
                            <div class="input-group-append">
                                <div class="input-group-text">
                                    <span class="fas fa-id-badge"></span>
                                </div>
                            </div>
                        </div>
                    </div>
                    {{-- KONTAK --}}
                    <div class="form-group">
                        <label for="kontak">Kontak</label>
                        <div class="input-group mb-3">
                            <input id="kontak" type="text" class="form-control" name="kontak"
                                placeholder="Kontak" value="{{ $data->kontak }}" readonly required
                                autocomplete="kontak">
                            <div class="input-group-append">
                                <div class="input-group-text">
                                    <span class="fas fa-user"></span>
                                </div>
                            </div>
                        </div>
                    </div>
                    {{-- HP sebagai password dan email --}}
                    <div class="form-group">
                        <label for="hp">HP</label>
                        <div class="input-group mb-3">
                            <input id="hp" type="text" class="form-control" name="hp" placeholder="HP"
                                value="{{ $data->hp }}" readonly required autocomplete="hp">
                            <div class="input-group-append">
                                <div class="input-group-text">
                                    <span class="fas fa-phone"></span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- /.card-body -->
            </form>
        </div>
    </div>
</div>
<div class="modal-footer justify-content-between">
    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
    <button type="button" class="btn btn-primary" data-dismiss="modal" id="actionInsertToUsers"
        onclick="actionInsertToUsers()">Insert</button>
</div>
