<template>
    <NcAppNavigation>
        <div v-if="loading">
            <div class="" style="margin-top: 20px; margin-bottom: 20px">
                <NcLoadingIcon :size="40" appearance="dark" name="Loading" />
            </div>
        </div>

        <div v-else>
            <div v-if="this.usuario_data.state == 1">
                <NcAppNavigationCaption
                :name="CalculoAhorro" :loading="true" style="margin-top: 20px; padding-left: 10px;padding-right: 10px;">
                    <template #actions>
                        <NcActionButton @click="showMoney()">
                            <template #icon>
                                <Eye v-if="!showmoney" :size="20" />
                                <EyeOff v-else :size="20" />
                            </template>
                        </NcActionButton>
                    </template>
                </NcAppNavigationCaption>
            </div>
        </div>

        <ul>  
            <NcAppNavigationItem name="Inicio" href="/index.php/apps/ahorrosgossler/">
                <template #icon>
                    <Home :size="20" />
                </template>
            </NcAppNavigationItem>

            <NcAppNavigationItem name="Solicitar" href="/index.php/apps/ahorrosgossler/solicitar">
                <template #icon>
                    <FileDocumentPlusOutline :size="20" />
                </template>
            </NcAppNavigationItem>

            <NcAppNavigationItem v-if="this.usuario_data.id_permision >= 2" name="Panel Gerencial" href="/index.php/apps/ahorrosgossler/panel">
                <template #icon>
                    <Security :size="20" />
                </template>
            </NcAppNavigationItem>
        </ul>
    </NcAppNavigation>
</template>




<script>
//Imports
import {
    NcLoadingIcon,
	NcAppNavigation,
	NcAppNavigationItem,
	NcAppNavigationCaption,
	NcActionButton, 
} from '@nextcloud/vue'

import Home from 'vue-material-design-icons/Home'
import axios from '@nextcloud/axios'
import { generateUrl } from '@nextcloud/router'
import { showError, showSuccess } from '@nextcloud/dialogs'
import { EventBus } from '../store/bus.js'

//Icons
import Magnify from 'vue-material-design-icons/Magnify.vue'
import Archive from 'vue-material-design-icons/Archive.vue'
import InformationSlabCircleOutline from 'vue-material-design-icons/InformationSlabCircleOutline'
import Eye from 'vue-material-design-icons/Eye'
import EyeOff from 'vue-material-design-icons/EyeOff'
import Security from 'vue-material-design-icons/Security'
import FileDocumentAlertOutline from 'vue-material-design-icons/FileDocumentAlertOutline'
import FileDocumentPlusOutline from 'vue-material-design-icons/FileDocumentPlusOutline'

export default {
	name: 'sidenavigation',

	components: {
        NcLoadingIcon,
		NcAppNavigation,
		NcAppNavigationItem,
		NcAppNavigationCaption,
		NcActionButton,
		Magnify,
		Archive,
        InformationSlabCircleOutline,
        Eye,
        EyeOff,
        Security,
        FileDocumentAlertOutline,
        FileDocumentPlusOutline,
        Home
    },

	data() {
		return {
			loading: true,
            showmoney: false,
            CalculoAhorro: 'Tu Ahorro: $ *********',
            usuario_data: [],
		}
	},

    async created() {
        // Get who i am
        const response = await axios.get('/ocs/v2.php/cloud/user');

        this.getInfo(response.data.ocs.data.id);
        
        EventBus.$emit('miData', response.data.ocs.data);
    },

    methods:{
        showMoney(){
            this.showmoney = !this.showmoney;
            if (this.showmoney == false){
                this.CalculoAhorro = 'Tu Ahorro: $ ********* '
            }
            else{
                let money = this.usuario_data;
                this.CalculoAhorro = ('Tu Ahorro: ' + Intl.NumberFormat("es-MX", {style: "currency", currency: "MXN"}).format(money.cantidad))
            }
        },

        async getInfo(username){
            try {
              let responses = await axios.post(
                    generateUrl('/apps/ahorrosgossler/userbyid'),
                    {
                        'id_user': username,
                    },
			    )       
                // Extraer el nombre de usuario del objeto de respuesta
                this.usuario_data = responses.data[0];
                
                EventBus.$emit('miDataAhorro', this.usuario_data);
                EventBus.$emit('loading', false);
                

            }catch (e) {
                console.error(e)
                showError(t('ahorrosgossler', 'Could not fetch your information'))
            }
            
            this.loading = false
        },
       
    }
}
</script>

<style lang="scss" scoped>
.center-screen {
  display: flex;
  justify-content: center;
  align-items: center;
  text-align: center;
  min-height: 100vh;
}
</style>