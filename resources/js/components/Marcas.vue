<template>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <!-- inicio do card de busca -->
                <card-component titulo="Busca de marcas">
                    <template v-slot:conteudo>
                        <div class="row">
                            <div class="col mb-3">
                                <input-container-component titulo="ID" id="inputId" id-help="idHelp" texto-ajuda="Opcional, Informe o ID do registro.">
                                    <input type="number" class="form-control" id="inputId" aria-describedby="idHelp" placeholder="ID" v-model="busca.id">
                                </input-container-component>
                            </div>
                            <div class="col mb-3">
                                <input-container-component titulo="Nome da Marca" id="inputNome" id-help="nomeHelp" texto-ajuda="Opcional, Informe o nome da marca.">
                                    <input type="text" class="form-control" id="inputNome" aria-describedby="nomeHelp" placeholder="Nome" v-model="busca.nome">
                                </input-container-component>
                            </div>
                        </div>
                    </template>
                    <template v-slot:rodape>
                        <button type="submit" class="btn btn-primary btn-sm float-end" @click="pesquisar()">Pesquisar</button>
                    </template>
                </card-component>
                <!-- fim do card de busca -->

                <!-- inicio do card de relação -->
                <card-component titulo="Relação de marcas">
                    <template v-slot:conteudo>
                        <table-component :dados="marcas.data" :titulos="{
                            id: {titulo: 'ID', tipo: 'texto'},
                            nome: {titulo: 'Nome', tipo: 'texto'},
                            imagem: {titulo: 'Imagem', tipo: 'imagem'},
                            created_at: {titulo: 'Data de criação', tipo: 'data'},
                        }"
                        :visualizar = "{visivel: true, dataToggle: 'modal', dataTarget: '#modalMarcaVisualizar'}"
                        :atualizar = "{visivel: true, dataToggle: 'modal', dataTarget: '#modalMarcaAtualizar'}"
                        :remover = "{visivel: true, dataToggle: 'modal', dataTarget: '#modalMarcaRemover'}"
                        ></table-component>
                    </template>
                    <template v-slot:rodape>
                        <div class="row">
                            <div class="col-10">
                                <paginate-component>
                                    <li v-for="link, key in marcas.links" :key="key" :class="link.active ? 'page-item active' : 'page-item'" @click="paginacao(link)">
                                        <a class="page-link" v-html="link.label"></a>
                                    </li>
                                </paginate-component>
                            </div>
                            <div class="col">
                                <button type="submit" class="btn btn-primary btn-sm float-end" data-bs-toggle="modal" data-bs-target="#modalMarca">Adicionar</button>
                            </div>
                        </div>
                    </template>
                </card-component>
                <!-- fim do card de relação -->
            </div>
        </div>
        <modal-component id="modalMarca" titulo="Adicionar Marca">
            <template v-slot:alertas>
                <alert-component tipo="success" :detalhes="transacaoDetalhes" titulo="Cadastro realizado com successo" v-if="transacaoStatus == 'adicionado'"></alert-component>
                <alert-component tipo="danger" :detalhes="transacaoDetalhes" titulo="Erro ao tentar cadastrar a Marca" v-if="transacaoStatus == 'erro'"></alert-component>
            </template>
            <template v-slot:conteudo>
                <div class="form-group">
                    <input-container-component titulo="Nome da Marca" id="novoNome" id-help="novoHelp" texto-ajuda="Opcional, Informe o nome da marca.">
                        <input type="text" class="form-control" id="novoNome" aria-describedby="novoHelp" placeholder="Nome" v-model="nomeMarca">
                    </input-container-component>
                </div>
                <div class="form-group">
                    <input-container-component titulo="Imagem" id="novoImagem" id-help="novoImagemHelp" texto-ajuda="Seleicone uma imagem no formato PNG.">
                        <input type="file" class="form-control" id="novoImagem" aria-describedby="novoImagemHelp" placeholder="Imagem" @change="carregarImagem($event)">
                    </input-container-component>
                </div>
            </template>
            <template v-slot:rodape>
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fechar</button>
                <button type="button" class="btn btn-primary" @click="salvar()">Salvar</button>
            </template>
        </modal-component>
        <modal-component id="modalMarcaVisualizar" titulo="Visualizar Marca">
            <template v-slot:alertas>
            </template>
            <template v-slot:conteudo>
                <input-container-component titulo="ID">
                    <input type="text" class="form-control" :value="$store.state.item.id" disabled>
                </input-container-component>
                <input-container-component titulo="Nome da marca">
                    <input type="text" class="form-control" :value="$store.state.item.nome" disabled>
                </input-container-component>
                <input-container-component titulo="Imagem">
                    <img v-if="$store.state.item.imagem" :src="'/storage/' + $store.state.item.imagem">
                </input-container-component>
                <input-container-component titulo="Data de Criação">
                    <input type="text" class="form-control" :value="$store.state.item.created_at" disabled>
                </input-container-component>
            </template>
            <template v-slot:rodape>
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fechar</button>
            </template>
        </modal-component>
        <modal-component id="modalMarcaRemover" titulo="Remover Marca">
            <template v-slot:alertas>
                <alert-component tipo="success" titulo="Transação realizada com sucesso" :detalhes="$store.state.transacao" v-if="$store.state.transacao.status == 'sucesso'"></alert-component>
                <alert-component tipo="danger" titulo="Erro na transação" :detalhes="$store.state.transacao" v-if="$store.state.transacao.status == 'erro'"></alert-component>
            </template>
            <template v-slot:conteudo v-if="$store.state.transacao.status != 'sucesso'">
                <input-container-component titulo="ID">
                    <input type="text" class="form-control" :value="$store.state.item.id" disabled>
                </input-container-component>
                <input-container-component titulo="Nome da marca">
                    <input type="text" class="form-control" :value="$store.state.item.nome" disabled>
                </input-container-component>
            </template>
            <template v-slot:rodape>
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fechar</button>
                <button type="button" class="btn btn-danger" @click="remover($store.state.item.id)" v-if="$store.state.transacao.status != 'sucesso'">Remover</button>
            </template>
        </modal-component>
        <modal-component id="modalMarcaAtualizar" titulo="Atualizar Marca">
            <template v-slot:alertas>
                <alert-component tipo="success" titulo="Transação realizada com sucesso" :detalhes="$store.state.transacao" v-if="$store.state.transacao.status == 'sucesso'"></alert-component>
                <alert-component tipo="danger" titulo="Erro na transação" :detalhes="$store.state.transacao" v-if="$store.state.transacao.status == 'erro'"></alert-component>
            </template>
            <template v-slot:conteudo>
                <div class="form-group">
                    <input-container-component titulo="Nome da Marca" id="atualizarNome" id-help="atualizarHelp" texto-ajuda="Opcional, Informe o nome da marca.">
                        <input type="text" class="form-control" id="atualizarNome" aria-describedby="atualizarHelp" placeholder="Nome" v-model="$store.state.item.nome">
                    </input-container-component>
                </div>
                <div class="form-group">
                    <input-container-component titulo="Imagem" id="atualizarImagem" id-help="atualizarImagemHelp" texto-ajuda="Seleicone uma imagem no formato PNG.">
                        <input type="file" class="form-control" id="atualizarImagem" aria-describedby="atualizarImagemHelp" placeholder="Imagem" @change="carregarImagem($event)">
                    </input-container-component>
                </div>
            </template>
            <template v-slot:rodape>
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fechar</button>
                <button type="button" class="btn btn-primary" @click="atualizar($store.state.item.id)">Atualizar</button>
            </template>
        </modal-component>
    </div>
</template>
<script>
export default {
    data(){
        return {
            nomeMarca: '',
            arquivoImagem: [],
            urlBase: "http://localhost:8000/api/v1/marca",
            urlPaginacao: '',
            urlFiltro: '',
            transacaoStatus: '',
            transacaoDetalhes: {},
            marcas: {data: []},
            busca: {
                id: '',
                nome: ''
            }
        }
    }, 
    methods: {
        paginacao(link){
            if (link.url) {
                this.urlPaginacao = link.url.split('?')[1]
                this.carregarLista()
            }
        },
        carregarImagem(event){
            this.arquivoImagem = event.target.files
        },
        salvar(){
            let formData = new FormData()
            formData.append('nome', this.nomeMarca)
            formData.append('imagem', this.arquivoImagem[0])
            
            let config = {
                headers: {
                    'Content-type': 'multipart/form-data',
                }
            }

            axios.post(this.urlBase, formData, config)
                .then(response => {
                    this.transacaoStatus = 'adicionado'
                    this.transacaoDetalhes = {
                        mensagem: 'ID do registro: ' + response.data.id
                    }
                })
                .catch(errors => {
                    this.transacaoStatus = 'erro'
                    this.transacaoDetalhes = {
                        mensagem: errors.response.data.message,
                        dados: errors.response.data.errors
                    }
                })
        },
        carregarLista(){

            let url = this.urlBase + '?' + this.urlPaginacao + this.urlFiltro

            axios.get(url)
                .then(response => {
                    this.marcas = response.data
                })
                .catch(errors => {
                    console.log(errors)
                })
        },
        pesquisar(){
            let filtro = ''

            for(let chave in this.busca){
                if(this.busca[chave]){
                    if(filtro != ''){
                        filtro += ';'
                    }
                    filtro += chave + ':like:' + this.busca[chave] + '%'
                }
            }

            if(filtro != ''){
                this.urlPaginacao = 'page=1'
                this.urlFiltro = '&filtro='+filtro
            } else {
                this.urlFiltro = ''
            }
            this.carregarLista()
        },
        remover(id) {
            let confirmacao = confirm('Tem certeza que deseja remover este registro?')

            if(!confirmacao) return false

            let formData = new FormData()
            formData.append('_method', 'delete')

            let url = this.urlBase + '/' + id

            axios.post(url, formData)
                .then(response => {
                    this.$store.state.transacao.status = 'sucesso'
                    this.$store.state.transacao.mensagem = response.data.msg
                    this.carregarLista()
                })
                .catch(errors => {
                    this.$store.state.transacao.status = 'erro'
                    this.$store.state.transacao.mensagem = errors.response.data.erro
                })
        },
        atualizar(id){
            let formData = new FormData()
            formData.append('_method', 'patch')
            formData.append('nome', this.$store.state.item.nome)

            if(this.arquivoImagem[0]){
                formData.append('imagem', this.arquivoImagem[0])
            }

            let url = this.urlBase + '/' + id

            let config = {
                headers: {
                    'Content-Type': 'multipart/form-data',
                }
            }

            axios.post(url, formData, config)
                .then(response => {
                    this.$store.state.transacao.status = 'sucesso'
                    this.$store.state.transacao.mensagem = 'Registro de marca atualizado com sucesso!'
                    atualizarImagem.value = ''
                    this.carregarLista()
                })
                .catch(errors => {
                    this.$store.state.transacao.status = 'erro'
                    this.$store.state.transacao.mensagem = errors.response.data.message
                    this.$store.state.transacao.dados = errors.response.data.errors
                })
        }
    },
    mounted(){
        this.carregarLista()
    }
}
</script>