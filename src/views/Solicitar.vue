<template>
    <NcContent app-name="ahorrosgossler">
        <navigator />
            <NcAppContent>
                <div v-if="this.userdataahorro.state == 0">
                    <div id="emptycontent">
                        <img src="../../img/crowesito-think.png" width="220px">
                            <h2>
                                {{ t('ahorrosgossler', 'Actualmente no es posible solicitar préstamos.') }}
                            </h2>
                    </div>
                </div>
                <div v-else-if="this.userdataahorro.state == 2">
                    <div id="emptycontent">
                        <img src="../../img/crowsito-like.png" width="220px">
                            <h2>
                                {{ t('ahorrosgossler', 'Tienes una solicitud pendiente.') }}
                            </h2>
                    </div>
                </div>
                <div v-else>
                    <div v-if="send">
                        <div id="emptycontent">
                            <img src="../../img/crowsito-like.png" width="220px">
                            <h2>
                                {{ t('ahorrosgossler', 'Se ha enviado tu solicitud exitosamente') }}
                            </h2>
                        </div>
                    </div>
                    <div v-else>
                        <div v-if="loading">
                        <div class="center-screen">
                            <NcLoadingIcon :size="64" appearance="dark" name="Loading on light background" />
                        </div>
                    </div>
                    <div v-else>
                        <div class="container">
                            <h2 class="board-title">
                                <FileDocumentPlusOutline :size="20" decorative class="icon" style="margin-top: 4px;" />
                                <span>Solicitud de ahorro</span>
                            </h2>
                        </div>
                        <div class="semi-container">
                            <div class="content-box">
                                <div style="padding: 25px; display: flex;">
                                    <div>
                                        <ul>
                                            <li>
                                                <p class="mb-0" >
                                                    El día 30 de Junio se hara el deposito del Préstamo del Fondo de Ahorro, mismo que no generará intereses.
                                                </p>
                                            </li>
                                            <li>
                                                <p class="mb-0">
                                                    El monto de dicho préstamo corresponderá hasta por el 90% del total de lo que tengan ahorrado en este momento, tanto la parte patronal como la del trabajador.
                                                </p>
                                            </li>
                                            <li>
                                                <p class="mb-0">
                                                    En este registro:
                                                    La cantidad es en pesos en caso de necesitar por porcentajes, podras agregarlo a las notas.
                                                </p>
                                            </li>
                                            <li>
                                                <p class="mb-0">
                                                    Por favor, verifica cuidadosamente la información, ya que la solicitud no se puede cancelar ni modificar.
                                                </p>
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="semi-container" style="margin-top: 20px;">
                            <div class="wrapper">
                                
                                <NcListItem :v-if="userdata.id"
                                    :name="userdata.displayname"
                                    :bold="false"
                                    one-line
                                    counterType="highlighted">
                                    <template #icon>
                                        <NcAvatar disable-menu :size="44" :user="userdata.id" :display-name="userdata.displayname" />
                                    </template>
                                    <template #subname>
                                        {{ userdata.email }}
                                    </template>
                                    <template #details>
                                        El 90% de tu ahorro es: {{ Intl.NumberFormat("es-MX", {style: "currency", currency: "MXN"}).format(((userdataahorro.cantidad/100)*90)) }}
                                    </template>
                                </NcListItem>

                                <NcTextField :value.sync="cantidad_solicitar"
                                    label="CANTIDAD A SOLICITAR"
                                    trailing-button-icon="close"
                                    :show-trailing-button="cantidad_solicitar !== ''"
                                    :success="cantidad_solicitar_state === 'success'"
                                    :error="cantidad_solicitar_state === 'error'"
                                    min="1"
                                    @input="changeCantidad()"
                                    @change="changeCantidad()"
                                    @trailing-button-click="clearCantidad">
                                    <CurrencyUsd :size="20" />
                                </NcTextField>

                                <NcTextArea :value.sync="notas"
                                    label="NOTAS"
                                    trailing-button-icon="close"
                                    :show-trailing-button="notas !== ''">
                                </NcTextArea>
                                
                                <br>
                                <div class="center" style="display: block;">
                                    <div>
                                        <NcCheckboxRadioSwitch :checked.sync="acept_terms" value="true" name="acept_terms">
                                                <strong style="font-size: 12px; margin-left: 10px;">
                                                    Autorizo a Gossler, S.C. a descontar de mi ahorro el monto correspondiente según las condiciones del préstamo.
                                                </strong> 
                                        </NcCheckboxRadioSwitch>
                                    </div>
                                    <div>
                                        <NcButton class="center" style="margin-top: 20px;" text="Crear Solicitud" type="primary" aria-label="Example text" @click="EnviarSolicitud()">
                                            + Crear Solicitud
                                        </NcButton>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    </div>
                </div>
            </NcAppContent>
        </NcContent>
    </template>
    
    <script>
    
    import { 
        NcContent,
        NcActionButton,
        NcAppContent,
        NcAppNavigation,
        NcAppNavigationItem,
        NcAppNavigationNew,
        NcAppNavigationCaption,
        NcAvatar,
        NcTextField,
        NcButton,
        NcListItem,
        NcTextArea,
        NcCheckboxRadioSwitch,
        NcLoadingIcon,
        NcNoteCard
        } from '@nextcloud/vue'
    
    import Plus from 'vue-material-design-icons/Plus'
    import CurrencyUsd from 'vue-material-design-icons/CurrencyUsd'
    import '@nextcloud/dialogs/styles/toast.scss'
    import { generateUrl } from '@nextcloud/router'
    import { showError, showSuccess } from '@nextcloud/dialogs'
    import axios from '@nextcloud/axios'
    import navigator from '../navigation/sidenavigation.vue'
    import Archive from 'vue-material-design-icons/Archive.vue'
    import CheckboxBlankCircle from 'vue-material-design-icons/CheckboxBlankCircle.vue'
    import FileDocumentPlusOutline from 'vue-material-design-icons/FileDocumentPlusOutline.vue'
    import { EventBus } from '../store/bus.js'
    import Router from 'vue-router';

    export default {
        name: 'App',
        components: {
            NcContent,
            NcActionButton,
            NcAppContent,
            NcAppNavigation,
            NcAppNavigationItem,
            NcAppNavigationNew,
            NcAppNavigationCaption,
            Plus,
            navigator,
            Archive,
            CheckboxBlankCircle,
            FileDocumentPlusOutline,
            NcAvatar,
            NcTextField,
            NcButton,
            NcListItem,
            NcTextArea,
            NcCheckboxRadioSwitch,
            NcLoadingIcon,
            NcNoteCard,
            CurrencyUsd
        },

       data() {
            return {
                loading: true,
                userdata: [],
                userdataahorro: [],
                cantidad_solicitar: "",
                notas: "",
                acept_terms: [],
                cantidad_solicitar_state: "",
                send: false,
            }
        },
        
        created() {
            EventBus.$on('miData', (data) => {
                this.userdata = data;
            });
            EventBus.$on('miDataAhorro', (data) => {
                this.userdataahorro = data;
            });
            EventBus.$on('loading', (data) => {
                this.loading = data;
            });
        },

        methods: {   
            changeCantidad(){
                const regex = /[a-zA-Z]/
                let containsCharactersAndLetters = regex.test(this.cantidad_solicitar)

                if(containsCharactersAndLetters != true){
                    this.cantidad_solicitar_state = "success"
                }
                else{
                    this.cantidad_solicitar_state = "error"
                    
                    showError(t('ahorrosgossler', 'La cantidad no permite letras ni simbolos'))
                }
            },

            clearCantidad(){
                this.cantidad_solicitar = ""
            },

            EnviarSolicitud(){
                
                    if(this.acept_terms == "true" && this.cantidad_solicitar != "" && this.cantidad_solicitar_state == "success"){
                        axios.post(
                        generateUrl('/apps/ahorrosgossler/EnviarSolicitud'),
                        {   
                            'id': this.userdataahorro.id,
                            'id_user': this.userdata.id,
                            'cantidad_solicitada': this.cantidad_solicitar,
                            'nota': this.notas,
                            'state': "2",
                            'cantidad_total': this.userdataahorro.cantidad,
                        },
                    ).then(
                            (response) => {
                                //console.log(Promise.resolve(response.data))
                                this.send = true
                                setTimeout(() => this.isHidden = false, 900);
                                window.location.href = generateUrl("/apps/ahorrosgossler");
                            },
                            (err) => {
                                showError(t('ahorrosgossler', 'Se ha producido un error, reporte al administrador'))
                                //console.log(Promise.reject(err))
                            },
                        )
                        /*.catch((err) => {
                            //console.log(Promise.reject(err))
                        })*/
                }
                else{
                    if(this.cantidad_solicitar > ((this.userdataahorro.cantidad/100)*90)){
                        this.cantidad_solicitar_state = "error"
                        showError(t('ahorrosgossler', 'Verifique la cantidad solicitada'))
                    }
                    else{
                        showError(t('ahorrosgossler', 'X - Verifique el formulario'))
                    }
                }
                },
            },  
        }
    </script>
<style scoped>    
    input[type='text'] {
        width: 100%;
    }

    textarea {
        flex-grow: 1;
        width: 100%;
    }
    #emptycontent, .emptycontent {
    margin-top: 20vh;
    }
    .center-screen {
	display: flex;
	justify-content: center;
	align-items: center;
	text-align: center;
	min-height: 100vh;
    }
    .center {
        margin: auto;
        padding: 10px;
    }
    .container{
        padding-left: 50px;
        padding-right: 24px;
    }
    .semi-container{
        padding-left: 24px;
        padding-right: 24px;
    }
    .board-title {
        margin-right: 10px;
        margin-top: 14px;
        font-size: 25px;
        display: flex;
        align-items: center;
        font-weight: bold;
        .icon {
            margin-right: 8px;
        }
    }
    .content-box{
        -moz-border-radius:50px;
        -webkit-border-radius:50px;
        background-color: gainsboro;    
        border-radius: 10px;
    }
    ul {
        list-style-type: circle;
    }
    .external-label {
        display: flex;
        width: 100%;
        margin-top: 1rem;
    }
    .external-label label {
        padding-top: 12px;
        padding-right: 14px;
        white-space: nowrap;
    }
    .wrapper {
        display: flex;
        gap: 4px;
        align-items: flex-end;
        flex-wrap: wrap;
    }
</style>
    