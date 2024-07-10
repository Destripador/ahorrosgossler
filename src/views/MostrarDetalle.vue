<template>
    <NcContent app-name="ahorrosgossler">
	    <navigator/>
		<NcAppContent>
			<div v-if="loading">
				<div class="center-screen">
					<NcLoadingIcon :size="64" appearance="dark" name="Loading on light background" />
				</div>
			</div>
			<div v-else>
				
			</div>
		</NcAppContent>
	</NcContent>
</template>

<script>
import navigator from '../navigation/sidenavigation.vue'

import { 
	NcActionButton,
	NcAppContent,
	NcAppNavigation,
	NcAppNavigationItem,
	NcAppNavigationNew,
	NcAppNavigationCaption,
    NcContent,
	NcLoadingIcon,
	NcButton,
	NcActions,
	NcListItem,
    NcAvatar,
    NcModal,
    NcSelect,
    NcProgressBar,
    NcActionLink
    } from '@nextcloud/vue'

import Plus from 'vue-material-design-icons/Plus'
import '@nextcloud/dialogs/styles/toast.scss'
import { generateUrl } from '@nextcloud/router'
import { showError, showSuccess } from '@nextcloud/dialogs'
import axios from '@nextcloud/axios'
import Archive from 'vue-material-design-icons/Archive.vue'
import CheckboxBlankCircle from 'vue-material-design-icons/CheckboxBlankCircle.vue'
import DatabaseExport from 'vue-material-design-icons/DatabaseExport.vue'
import { EventBus } from '../store/bus.js'
import { ref } from 'vue'

export default {
	name: 'App',
	components: {
		NcActionButton,
		NcAppContent,
		NcAppNavigation,
		NcAppNavigationItem,	
		NcAppNavigationNew,
		NcAppNavigationCaption,
		Plus,
		navigator,
        NcContent,
        NcLoadingIcon,
		NcButton,
		NcActions,
		NcListItem,
		Archive,
		CheckboxBlankCircle,
        NcAvatar,
        NcModal,
        DatabaseExport,
        NcSelect,
        NcProgressBar,
        NcActionLink
	},

    setup() {
		return {
			modalRef: ref(null),
		}
	},

	data() {
		return {
            Id: this.$route.query.page,
            loading: true,
            userdata: [],
            userdataahorro: [],
            send: false,
		}
	},

	mounted() {
        console.log(this.Id)
	},

    created() {
        EventBus.$on('miData', (data) => {
            this.userdata = data;
        });
        EventBus.$on('miDataAhorro', (data) => {
            this.userdataahorro = data;
            if(this.userdataahorro.id_permision < 3){
                window.location.href = generateUrl("/apps/ahorrosgossler/");
                console.log("nopuedes estar aqui ", this.userdataahorro.id_permision)
            }
            else{
			    this.loading = false
            }
        });
    },

	methods: {
		async getSolicitudId(id){
			//user.data.ocs.data.id <- get username
			try {
				let response = await axios.get(generateUrl('apps/ahorrosgossler/getSolicitudId/', ));
	
				// Extraer el nombre de usuario del objeto de respuesta
				console.log(response)
				this.historial = response.data
			}catch (e) {
				console.error(e)
				showError(t('ahorrosgossler', 'Could not fetch your information'))
			}
		},

        showModal() {
			this.modal = true
		},
		closeModal() {
			this.modal = false
		},
	},
}
</script>
<style scoped>
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
    .modal__content {
        margin: 50px;
    }

    .modal__content h2 {
        text-align: center;
    }
</style>
