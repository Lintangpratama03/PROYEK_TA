 <div class="btn-group">
     <a class="btn btn-primary btn-xs btn-data" data-id_user="{{ $id }}" onclick="show(this)" type="button">
         <i class="fa fa-eye" aria-hidden="true"></i> Detail
     </a>
     <a class="btn btn-info btn-xs btn-data" data-id_user="{{ $id }}" onclick="edit(this)" type="button">
         <i class="fa fa-pencil" aria-hidden="true"></i> Edit Data
     </a>
     @if ($aktif == '1')
         <a class="btn btn-secondary btn-xs btn-data" data-id_user="{{ $id }}" onclick="banned(this)" type="button">
             <i class="fa fa-ban" aria-hidden="true"></i> Nonaktifkan User
         </a>
     @else
         <a class="btn btn-success btn-xs btn-data" data-id_user="{{ $id }}" onclick="unbanned(this)" type="button">
             <i class="fa fa-check-circle" aria-hidden="true"></i> Aktifkan User
         </a>
     @endif
 </div>
