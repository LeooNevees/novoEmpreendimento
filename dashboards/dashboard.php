<html>

<head>
    <title>Ipeças - Dashboards</title>    
    <!--chart.js-->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.9.4/Chart.bundle.js" integrity="sha512-zO8oeHCxetPn1Hd9PdDleg5Tw1bAaP0YmNvPY8CwcRyUk7d7/+nyElmFrB6f7vg4f7Fv4sui1mcep8RIEShczg==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.9.4/Chart.bundle.min.js" integrity="sha512-SuxO9djzjML6b9w9/I07IWnLnQhgyYVSpHZx0JV97kGBfTIsUYlWflyuW4ypnvhBrslz1yJ3R+S14fdCWmSmSA==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.9.4/Chart.min.css" integrity="sha512-/zs32ZEJh+/EO2N1b0PEdoA10JkdC3zJ8L5FTiQu82LR9S/rOQNfQN7U59U9BC12swNeRAz3HSzIL2vpp4fv3w==" crossorigin="anonymous" referrerpolicy="no-referrer" />
</head>

<body style="background-color: #eeecec;">
    <?php
        try {
            if (!(include '/var/www/html/novoEmpreendimento/navbar.php') || 
                !(include '/var/www/html/novoEmpreendimento/sistema.php')) {
                throw new Exception('Erro no include de páginas');
            }
        } catch (Exception $ex) {
            trigger_error($ex->getMessage());
            header('location: error404.php');
        }
    ?>
    <div class='album py-5 bg-index'>
        <div class='container'>
            <h4 class='text-center card-titulo'>Vendas</h4>
            <div class='row row-cols-1 row-cols-sm-2 row-cols-lg-2 g-2'>
                <div class='col'>
                    <div class='card shadow-sm'>
                        <canvas id="vendasMensais" width="400" height="400"></canvas>
                        <div class='card-footer text-muted'>
                            <p class='color-vermelho text-center'>Vendas Mensais</p>
                            <p class='card-text text-center'>Total de vendas realizadas durante o ano <?php echo date('Y') ?></p>
                        </div>
                    </div>
                </div>
                <div class='col'>
                    <div class='card shadow-sm'>
                        <canvas id="vendasDiarias" width="400" height="400"></canvas>
                        <div class='card-footer text-muted'>
                            <p class='color-vermelho text-center'>Vendas Diárias</p>
                            <p class='card-text text-center'>Total de vendas realizadas durante os dias do mês atual</p>
                        </div>
                    </div>
                </div>
            </div><br><br>
            <h4 class='text-center card-titulo'>Parceiros de Negócios</h4>
            <div class='row row-cols-1 row-cols-sm-2 row-cols-lg-2 g-2'>
                <div class='col'>
                    <div class='card shadow-sm'>
                        <canvas id="vendedores" width="400" height="400"></canvas>
                        <div class='card-footer text-muted'>
                            <p class='color-vermelho text-center'>Vendedores</p>
                            <p class='card-text text-center'>Vendedores mais ativos</p>
                        </div>
                    </div>
                </div>
                <div class='col'>
                    <div class='card shadow-sm'>
                        <canvas id="compradores" width="400" height="400"></canvas>
                        <div class='card-footer text-muted'>
                            <p class='color-vermelho text-center'>Compradores</p>
                            <p class='card-text text-center'>Compradores mais ativos</p>
                        </div>
                    </div>
                </div>
            </div><br><br>
            <h4 class='text-center card-titulo'>Produtos</h4>
            <div class='row row-cols-1 row-cols-sm-2 row-cols-lg-2 g-2'>
                <div class='col'>
                    <div class='card shadow-sm'>
                        <canvas id="gruposProdutos" width="400" height="400"></canvas>
                        <div class='card-footer text-muted'>
                            <p class='color-vermelho text-center'>Grupo de Produtos</p>
                            <p class='card-text text-center'>Grupos de produtos mais requisitados</p>
                        </div>
                    </div>
                </div>
                <div class='col'>
                    <div class='card shadow-sm'>
                        <canvas id="produtos" width="400" height="400"></canvas>
                        <div class='card-footer text-muted'>
                            <p class='color-vermelho text-center'>Produtos</p>
                            <p class='card-text text-center'>Produtos mais vendidos</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        function objectToArray(object) {
            let result = [];
            for(key of Object.keys(object)){
                result.push(object[key]);
            }
            return result;
        }

        function buscarVendas() {
            localStorage.clear();
            $.ajax({
                async: false,
                url: '/novoEmpreendimento/sale/ajax_dashboard.php',
                type: 'post',
                data: {
                    'tipo': 'VENDAS'
                },
                dataType: 'json',
                success: function (resposta) {
                    if(resposta.status == 'ERRO'){
                        alert(resposta.mensagem);
                        return false;
                    }
                    //ENVIO DOS DADOS PARA LOCALSTORAGE
                    vendasMensaisDashboard(resposta);
                    vendasDiariasDashboard(resposta);
                    vendedoresMaisAtivos(resposta);
                    compradoresMaisAtivos(resposta);
                    grupoAtivos(resposta);
                    produtosMaisVendidos(resposta);
                    return true;
                }
            })
        }

        function vendasMensaisDashboard(resposta) {
            if(resposta == ''){
                alert('Erro ao carregar Vendas Mensais Dashboard');
                return false;
            }
            var contador = Object.keys(resposta.array_mes).length;
            let newData = [];
            for (let i = 0; i < contador; i++) {
                newData[i] = 0;
                if(typeof resposta.vendas_mensais[i+1] != "undefined"){
                    newData[i] = resposta.vendas_mensais[i+1]['quantidade'];   
                }
            }

            var vendasMensais = {
                label: 'Vendas Mensais',
                borderColor: '#87CEFA',
                backgroundColor: 'transparent',
                data: newData
            };

            localStorage.setItem('vendas_mensais_dashboard', JSON.stringify(vendasMensais));
            localStorage.setItem('meses_dashboard', JSON.stringify(resposta.array_mes));

            return true;
        }

        function vendasDiariasDashboard(resposta) {
            if(resposta == ''){
                alert('Erro ao carregar Vendas Diarias Dashboard');
                return false;
            }
            var contadorDay = Object.keys(resposta.array_dia).length;
            let newDay = [];
            for (let a = 0; a < contadorDay; a++) {
                newDay[a] = 0;
                if(typeof resposta.vendas_diarias[a+1] != "undefined"){
                    newDay[a] = resposta.vendas_diarias[a+1]['quantidade'];   
                }
            }

            var vendasDiarias = {
                label: 'Vendas Diárias',
                borderColor: '#87CEFA',
                backgroundColor: 'transparent',
                data: newDay
            };

            localStorage.setItem('vendas_diarias_dashboard', JSON.stringify(vendasDiarias));
            localStorage.setItem('dias_dashboard', JSON.stringify(resposta.array_dia));

            return true;
        }

        function vendedoresMaisAtivos(resposta) {
            if(resposta == ''){
                alert('Erro ao carregar Vendedores Mais Ativos');
                return false;
            }
            let newNomeVendedor = [];
            let newQuantidadeVendedor = [];
            var vendedorObj = Object.keys(resposta.top_vendedor);
            for (let b = 0; b < 5; b++) {
                newNomeVendedor[b] = 'Anônimo';
                newQuantidadeVendedor[b] = 0;
                
                if(typeof resposta.top_vendedor[vendedorObj[b]] != "undefined"){
                    newNomeVendedor[b] = resposta.top_vendedor[vendedorObj[b]]['nome_parceiro']; 
                    newQuantidadeVendedor[b] = resposta.top_vendedor[vendedorObj[b]]['quantidade']; 
                }
            }

            var vendedores = {
                label: 'Vendedores',
                borderColor: '#87CEFA',
                backgroundColor: [
                    'rgb(255, 99, 132)',
                    'rgb(75, 192, 192)',
                    'rgb(255, 205, 86)',
                    'rgb(201, 203, 207)',
                    'rgb(54, 162, 235)'
                ],
                data: newQuantidadeVendedor
            };

            localStorage.setItem('nome_vendedores_ativos', JSON.stringify(newNomeVendedor));
            localStorage.setItem('quantidade_vendedores_ativos', JSON.stringify(vendedores));

            return true;

        }

        function compradoresMaisAtivos(resposta) {
            if(resposta == ''){
                alert('Erro ao carregar Compradores Mais Ativos');
                return false;
            }
            let newNomeComprador = [];
            let newQuantidadeComprador = [];
            var compradorObj = Object.keys(resposta.top_comprador);
            for (let c = 0; c < 5; c++) {
                newNomeComprador[c] = 'Anônimo';
                newQuantidadeComprador[c] = 0;
                
                if(typeof resposta.top_comprador[compradorObj[c]] != "undefined"){
                    newNomeComprador[c] = resposta.top_comprador[compradorObj[c]]['nome_parceiro']; 
                    newQuantidadeComprador[c] = resposta.top_comprador[compradorObj[c]]['quantidade']; 
                }
            }

            var compradores = {
                label: 'Compradores',
                borderColor: '#87CEFA',
                backgroundColor: [
                    'rgb(0,255,255)',
                    'rgb(0,100,0)',
                    'rgb(210,105,30)',
                    'rgb(75,0,130)',
                    'rgb(128,0,0)'
                ],
                data: newQuantidadeComprador
            };

            localStorage.setItem('nome_compradores_ativos', JSON.stringify(newNomeComprador));
            localStorage.setItem('quantidade_compradores_ativos', JSON.stringify(compradores));

            return true;

        }

        function grupoAtivos(resposta) {
            if(resposta == ''){
                alert('Erro ao carregar Grupos');
                return false;
            }
            let newNomeGrupo = [];
            let newQuantidadeGrupo = [];
            var grupoObj = Object.keys(resposta.top_grupos);
            for (let d = 0; d < grupoObj.length; d++) {
                newNomeGrupo[d] = 'Desconhecido';
                newQuantidadeGrupo[d] = 0;
                
                if(typeof resposta.top_grupos[d+1] != "undefined"){
                    newNomeGrupo[d] = resposta.top_grupos[d+1]['nome']; 
                    newQuantidadeGrupo[d] = resposta.top_grupos[d+1]['quantidade']; 
                }
            }

            var grupos = {
                label: 'Grupos',
                borderColor: '#87CEFA',
                backgroundColor: [
                    'rgb(0,255,255)',
                    'rgb(0,100,0)',
                    'rgb(210,105,30)',
                    'rgb(75,0,130)',
                    'rgb(128,0,0)'
                ],
                hoverOffset: 4,
                data: newQuantidadeGrupo
            };

            localStorage.setItem('nome_grupos_ativos', JSON.stringify(newNomeGrupo));
            localStorage.setItem('quantidade_produtos_grupos', JSON.stringify(grupos));

            return true;
        }

        function produtosMaisVendidos(resposta) {
            if(resposta == ''){
                alert('Erro ao carregar Grupos');
                return false;
            }
            let newNomeProduto = [];
            let newQuantidadeProdutoVendido = [];
            var produtoObj = Object.keys(resposta.top_produtos);
            for (let e = 0; e < 5; e++) {
                newNomeProduto[e] = 'Desconhecido';
                newQuantidadeProdutoVendido[e] = 0;
                
                if(typeof resposta.top_produtos[produtoObj[e]] != "undefined"){
                    newNomeProduto[e] = resposta.top_produtos[produtoObj[e]]['nome_produto']; 
                    newQuantidadeProdutoVendido[e] = resposta.top_produtos[produtoObj[e]]['quantidade'];
                }
            }

            var produtos = {
                label: 'Produtos',
                backgroundColor: [
                'rgba(255, 99, 132, 0.2)',
                'rgba(255, 159, 64, 0.2)',
                'rgba(255, 205, 86, 0.2)',
                'rgba(75, 192, 192, 0.2)',
                'rgba(54, 162, 235, 0.2)'
                ],
                borderColor: [
                'rgb(255, 99, 132)',
                'rgb(255, 159, 64)',
                'rgb(255, 205, 86)',
                'rgb(75, 192, 192)',
                'rgb(54, 162, 235)'
                ],
                borderWidth: 1,
                data: newQuantidadeProdutoVendido
            };

            localStorage.setItem('nome_produtos', JSON.stringify(newNomeProduto));
            localStorage.setItem('quantidade_produtos', JSON.stringify(produtos));

            return true;
        }

        function chart(idInput, tipo, nomeColunas, dados){
            var ctx = document.getElementById(idInput);
            new Chart(ctx, {
                type: tipo,
                data: {
                    labels: nomeColunas,
                    datasets: [dados],
                },
                options: {
                    elements: {
                        line: {
                            tension: 0
                        }
                    }
                }
            })
        }
        
        Promise.all([buscarVendas()])
            .then(function (values) {
                var getMeses = JSON.parse(localStorage.getItem('meses_dashboard'));
                var getVendasMensais = JSON.parse(localStorage.getItem('vendas_mensais_dashboard'));
                chart('vendasMensais', 'line', objectToArray(getMeses), getVendasMensais);

                var getDia = JSON.parse(localStorage.getItem('dias_dashboard'));
                var getVendasDiarias = JSON.parse(localStorage.getItem('vendas_diarias_dashboard'));
                chart('vendasDiarias', 'line', objectToArray(getDia), getVendasDiarias);

                var getNomeVendedores = JSON.parse(localStorage.getItem('nome_vendedores_ativos'));
                var getQuantidadeVendedores = JSON.parse(localStorage.getItem('quantidade_vendedores_ativos'));
                chart('vendedores', 'polarArea', objectToArray(getNomeVendedores), getQuantidadeVendedores);

                var getNomeCompradores = JSON.parse(localStorage.getItem('nome_compradores_ativos'));
                var getQuantidadeCompradores = JSON.parse(localStorage.getItem('quantidade_compradores_ativos'));
                chart('compradores', 'polarArea', objectToArray(getNomeCompradores), getQuantidadeCompradores);

                var getNomeGrupos = JSON.parse(localStorage.getItem('nome_grupos_ativos'));
                var getProdutosGrupos = JSON.parse(localStorage.getItem('quantidade_produtos_grupos'));
                chart('gruposProdutos', 'doughnut', objectToArray(getNomeGrupos), getProdutosGrupos);

                var getNomeProdutos = JSON.parse(localStorage.getItem('nome_produtos'));
                var getQuantidadeProdutos = JSON.parse(localStorage.getItem('quantidade_produtos'));
                chart('produtos', 'bar', objectToArray(getNomeProdutos), getQuantidadeProdutos);
            });
    </script>

    <!-- <script>
        // var teste = {
        //     label: 'Vendas',
        //     borderColor: '#87CEFA',
        //     backgroundColor: 'transparent',
        //     data: [0,1,2,3,2,3,4,5]
        // };
        // chart('myChart', 'line', getMeses, getVendas);

        var meses = buscarMeses();
        var vendas = buscarVendas();

        console.log('Iniciando preenchimento do dashboard');
        var ctx = document.getElementById('myChart');
        var myChart = new Chart(ctx, {
            type: 'line',
            data: {
                labels: meses,
                datasets: [{
                    label: 'Compras',
                    borderColor: '#87CEFA',
                    backgroundColor: 'transparent',
                    data: [0, 1, 2, 3, 4, 6, 5, 4],
                },
                {
                    label: 'Vendas',
                    borderColor: 'red',
                    backgroundColor: 'transparent',
                    data: [15, 14, 12, 10, 11, 10],
                }],
            },
            options: {
                elements: {
                    line: {
                        tension: 0
                    }
                }
            }
        });
    </script> -->
</body>

</html>