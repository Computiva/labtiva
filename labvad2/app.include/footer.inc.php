<div class="modal boxLogout">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title">Atenção!</h4>
            </div>
            <div class="modal-body">
                <p id="mensagem-local">Tem certeza que deseja sair do LabVad?</p>
            </div>
            <div class="modal-footer">
                <button type="button" id="btLogoutSim" class="btn btn-primary" data-dismiss="modal">Sim</button>
                <button type="button" id="btLogoutNao" class="btn btn-danger" data-dismiss="modal">Não</button>
            </div>
        </div>
    </div>
</div>


</div>

<script>
    var seguranca = false;
    $(document).ready(function () {        
        $("#logout").on("click", function () {
            var localAtual = document.location.href;
            if (document.location.href.indexOf('laboratorio.php') < 0) {
                $(".boxLogout").modal('show');                
            }
            return false;
        });
        $("#btLogoutSim").on("click", function () {
            seguranca = true;
            document.location.href = $("#logout").attr('href');
        });
        $("#btLogoutNao").on("click", function () {
            
        });
    });
    
    window.onbeforeunload = function () {
        if (seguranca) {
            var codigo1, codigo2;
            codigo1   = localStorage.getItem("codigo");
            codigo2   = localStorage.getItem("comparaCodigo");
            alterado  = localStorage.getItem("alterado");
            seguranca = false;
            if ((codigo1 !== codigo2) || (alterado === "s")) {
                return 'Se você fechar essa janela irá perder todo o código que não foi salvo!';
            }
        }
    }
</script>	
</body>
</html>
