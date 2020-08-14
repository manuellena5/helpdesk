<div class="modal fade bd-example-modal-lg" id="exampleModalLong" tabindex="-1" role="dialog" aria-labelledby="exampleModalLongTitle" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <?php if (!isset($agenda)) {
          ?>
          <input id="btnImprimir" onclick="imprimirElemento('cuerpo')" type="button"  class="btn btn-default" value="Imprimir"> 
          <?php } ?>
          <h5 class="modal-title" id="exampleModalLongTitle"></h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
           
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
       
          
        
        <div class="modal-body" id="cuerpo"> <?php if ((isset($agenda)) && (($agenda)=="1")) {
          include("agenda.php");
        } ?></div>
        
        <div class="modal-footer" id="piemodal">
          <?php if (!isset($agenda)) {
          ?>
          <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
          <?php }else { ?>
          <button type="button" id="seleccion" class="close" data-dismiss="modal" aria-label="Close">Seleccion</button>
          <?php } ?>
        </div>
      </div>
    </div>
</div>