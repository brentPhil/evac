<?php include 'main.php' ?>

            <!-- /.content-header -->
            <!-- Main content -->
            <section class="content">
               <div class="container-fluid">
                  <div class="card card-info">
                     <!-- form start -->
                     <form>
                        <div class="card-body">
                           <div class="row">
                              <div class="col-md-3">
                                 <div class="card-header">
                                    <span class="fa fa-globe-asia"> Calamity Information</span>
                                 </div>
                                 <div class="row">
                                    <div class="col-md-12">
                                       <div class="form-group">
                                          <label>Calamity Name</label>
                                          <input type="text" class="form-control" placeholder="Calamity name">
                                       </div>
                                    </div>
                                 </div>
                                 <div class="col-md-12">
                                    <button type="submit" class="btn btn-primary">Save</button>
                                    <button type="submit" class="btn btn-danger">Cancel</button>
                                 </div>
                              </div>

                              <div class="col-md-9" style="border-left: 1px solid #ddd;">
                                 <table id="example1" class="table table-bordered table-hover">
                                    <thead>
                                       <tr>
                                          <th>Calamity Name</th>
                                          <th class="">Action</th>
                                       </tr>
                                    </thead>
                                    <tbody>
                                       <tr>
                                          <td>Typhoon</td>
                                          <td class="text-right">
                                             <a class="btn btn-sm btn-success" href="#"><i class="fa fa-edit"></i> edit</a>
                                             <a class="btn btn-sm btn-danger" href="#" data-toggle="modal" data-target="#delete"><i
                                                   class="fa fa-trash-alt"></i> delete</a>
                                          </td>
                                       </tr>
                                       <tr>
                                          <td>Flood</td>
                                          <td class="text-right">
                                             <a class="btn btn-sm btn-success" href="#"><i class="fa fa-edit"></i> edit</a>
                                             <a class="btn btn-sm btn-danger" href="#" data-toggle="modal" data-target="#delete"><i
                                                   class="fa fa-trash-alt"></i> delete</a>
                                          </td>
                                       </tr>
                                       <tr>
                                          <td>Earthquake</td>
                                          <td class="text-right">
                                             <a class="btn btn-sm btn-success" href="#"><i class="fa fa-edit"></i> edit</a>
                                             <a class="btn btn-sm btn-danger" href="#" data-toggle="modal" data-target="#delete"><i
                                                   class="fa fa-trash-alt"></i> delete</a>
                                          </td>
                                       </tr>
                                       </tbody>
                                 </table>
                              </div>
                           </div>
                        </div>
                     </form>
                  </div>
               </div>
            </section>


            <div id="delete" class="modal animated rubberBand delete-modal" role="dialog">
               <div class="modal-dialog modal-dialog-centered">
                  <div class="modal-content">
                     <div class="modal-body text-center">
                        <img src="../asset/img/sent.png" alt="" width="50" height="46">
                        <h3>Are you sure want to delete this Calamity type?</h3>
                        <div class="m-t-20"> <a href="#" class="btn btn-white" data-dismiss="modal">Close</a>
                           <button type="submit" class="btn btn-danger">Delete</button>
                        </div>
                     </div>
                  </div>
               </div>
            </div>

<?php include 'footer.php' ?>         