@extends('layouts.default') 
@section('content')
<div class="content">
    <div class="page-inner">
        <div class="page-header">
            <h4 class="page-title">Data Keluarga</h4>

        </div>
        <div class="row">

            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <div class="card-title">Edit Data Keluarga</div>
                    </div>
                    <div class="card-body">
                        <form role="form" method="post"  action="{{url('kependudukan/keluarga/update/'.$keluarga->keluarga_id)}}" >
                            @csrf
                            <div class="form-group form-inline">
								<label class="col-md-3 label-control"><b>Kepala Keluarga</b></label>
								<div class="col-md-9 p-0">
                                        <select class="form-control" name="kepala_keluarga"> 
                                            <option> - Pilih -</option>
                                            @foreach ($penduduk as $item)
                                            <option value="{{$item->penduduk_id}}" 
                                            {{$keluarga->kepala_keluarga == $item->penduduk_id?"selected":""}}>{{$item->full_name}}</option>
                                            @endforeach;
                                        </select>
								</div>
							</div>
                            <div class="form-group form-inline">
								<label class="col-md-3 label-control"><b>No. Kartu Keluarga</b></label>
								<div class="col-md-9 p-0">
									<input type="text" class="form-control input-full" name="no_kk" placeholder="No. Kartu Keluarga" value="{{$keluarga->no_kk}}">
								</div>
							</div>
                          
                            <div class="form-group form-inline">
								<label class="col-md-3 label-control"><b>Alamat</b></label>
								<div class="col-md-9 p-0">
                                    <textarea name="alamat_keluarga" class="form-control input-full"  placeholder="Alamat">{{$keluarga->alamat_keluarga}}</textarea>
								</div>
							</div>
                            <div class="form-group form-inline">
								<label class="col-md-3 label-control"><b>Dusun</b></label>
								<div class="col-md-9 p-0">
                                    <select class="form-control" name="wilayah_dusun" onchange="GetRW(this)"> 
                                        <option> - Pilih -</option>
                                        @foreach ($dusun as $item)
                                        <option value="{{$item->wilayah_id}}" 
                                        {{$keluarga->wilayah_dusun == $item->wilayah_id?"selected":""}} >{{$item->wilayah_nama}}</option>
                                        @endforeach;
                                    </select>
								</div>
							</div>
                            <div class="form-group form-inline">
								<label class="col-md-3 label-control"><b>RW</b></label>
								<div class="col-md-9 p-0">
                                    <select class="form-control" name="wilayah_rw" id="wilayah_rw"  onchange="GetRT(this)"> 
                                        <option> - Pilih -</option>
                                        @foreach ($rw as $item)
                                        <option value="{{$item->wilayah_id}}" 
                                        {{$keluarga->wilayah_rw == $item->wilayah_id?"selected":""}} >{{$item->wilayah_nama}}</option>
                                        @endforeach;
                                    </select>
								</div>
							</div>
                            <div class="form-group form-inline">
								<label class="col-md-3 label-control"><b>RT</b></label>
								<div class="col-md-9 p-0">
                                    <select class="form-control" name="wilayah_rt" id="wilayah_rt"> 
                                        <option> - Pilih -</option>
                                        @foreach ($rt as $item)
                                        <option value="{{$item->wilayah_id}}" 
                                        {{$keluarga->wilayah_rt == $item->wilayah_id?"selected":""}} >{{$item->wilayah_nama}}</option>
                                        @endforeach;
                                    </select>
                                 </div>
							</div>
                            <div class="form-group">    
                                <div class="col-md-3 col-md-offset-9">
                                    <button type="submit" value="Submit" class="btn btn-primary">Submit</button>
                                    <a href="{{redirect()->back()->getTargetUrl()}}" class="btn btn-danger">Kembali</a>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
   var url = "{{url('kependudukan/penduduk/')}}";
   function GetRW(evnt){
        $.get(url+"/get_wilayah/"+evnt.value+"/rw", function(data, status){
           
            $('#wilayah_rw')
            .find('option')
            .remove()
            .end()
            .append('<option>- Pilih -</option>');

            for(i=0;i < data.length;i++)
                {   
                    $('#wilayah_rw').append(`<option value="${data[i].wilayah_id}"> 
                                            ${data[i].wilayah_nama} 
                                        </option>`); 
                }
        
        });
   }
   function GetRT(evnt){
        $.get(url+"/get_wilayah/"+evnt.value+"/rt", function(data, status){
            $('#wilayah_rt')
            .find('option')
            .remove()
            .end()
            .append('<option>- Pilih -</option>');
            for(i=0;i < data.length;i++)
                {   
                    $('#wilayah_rt').append(`<option value="${data[i].wilayah_id}"> 
                                            ${data[i].wilayah_nama} 
                                        </option>`); 
                }
        
        });
   }
</script>
@stop