<template>
    <NcContent app-name="ahorrosgossler">
	    <navigator/>
		<NcAppContent>

			<div class="container">
				<h2 class="board-title">
					<Cash :size="20" decorative class="icon" style="margin-top: 4px;" />
					<span>Ahorro Gossler</span>
				</h2>
			</div>

			<div class="container-note">
				<NcNoteCard type="info">
					<h2>¡Hurra!</h2>
					<p>
						Bienvenido al modulo. Actualmente el modulo se encuentra en modo de prueba, por lo que algunas opciones están limitadas.
						Si encuentras algún error, por favor repórtalo a <strong>luis.alvarado@crowe.mx</strong> Es muy importante para mejorar nuestro servicio.
					</p>
				</NcNoteCard>
			</div>

			<div class="rsg container-note">
				<div style="display: flex;" class="center" id="inner">
					
					<div class="center">
						<h2>
							Solicita tu ahorro
						</h2>
						<p>
							¡Ahora puedes solicitar tu adelanto del fondo de ahorro directamente desde NUBE GOSSLER!
						</p>
						<div style="display: flex; flex-direction: column; gap: 12px; padding-left: 20px; margin-right: 20px; margin-top: 20px;">
							<div v-if="this.userdata.state == 1" style="margin-top: 10px;">
								<NcButton aria-label="center (default)" type="secondary" wide href="/index.php/apps/ahorrosgossler/solicitar">
									<template #icon>
										<Check :size="20" />
									</template>
									Ya puedes generar tu solicitud!
								</NcButton>
							</div>
							<div v-else-if="this.userdata.state == 0">
								<NcButton aria-label="center (default)" type="error" wide>
									<template #icon>
										<ClockAlertOutline :size="20" />
									</template>
									No tienes habilitado este modulo
								</NcButton>
							</div>
							<div v-else-if="this.userdata.state == 2">
								<NcButton aria-label="center (default)" type="success" wide>
									<template #icon>
										<ClockAlertOutline :size="20" />
									</template>
									Ya solicitaste tu ahorro! <br> Espera tu respuesta
								</NcButton>
							</div>
						</div>
					</div>
				</div>
			</div>
			
			<div class="rsh">
				<historial/>
			</div>

		</NcAppContent>
	</NcContent>
</template>


<script>
//barra lateral
import navigator from '../navigation/sidenavigation.vue'
import historial from './Historial.vue'

//iconos
import Cash from 'vue-material-design-icons/Cash'
import ClockAlertOutline from 'vue-material-design-icons/ClockAlertOutline'
import Check from 'vue-material-design-icons/Check'

//modulos
import { NcAppContent, NcContent, NcNoteCard, NcRelatedResourcesPanel, NcButton } from '@nextcloud/vue'
import { EventBus } from '../store/bus.js'

export default {
	name: 'App',
	components: { navigator, historial, Cash, ClockAlertOutline, Check, NcAppContent, NcContent, NcNoteCard, NcRelatedResourcesPanel, NcButton  },
	data() {
        return {
            loading: true,
            userdata: [],
        }
    },
	created() {
        EventBus.$on('miDataAhorro', (data) => {
            this.userdata = data;
        });
        EventBus.$on('loading', (data) => {
            this.loading = data;
        });
    },
}


</script>

<style scoped>
	.container {
		padding-left: 60px;
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
	.container-note {
		padding-left: 20px;
		padding-right: 20px;
	}
	.rsg {
		padding-top: 16px;
		padding-bottom: 16px;
		border: 1px solid rgb(232, 232, 232);
		border-radius: 3px;
		display: flex;
		margin-left: 20px;
  		margin-right: 20px;
		width: auto;
	}
	.rsh {
		padding-top: 16px;
		padding-bottom: 16px;
		border: 1px solid rgb(232, 232, 232);
		border-radius: 3px;
		margin-left: 20px;
  		margin-right: 20px;
		margin-top: 20px;
		width: auto;
	}
	.center {
		margin: auto;
		width: 70%;
		padding: 10px;
	}
	#inner {
    margin: 0 auto;
    padding: 0 20px;
    width: 80%;
    border-radius: 10px;
    padding: 20px;
    }

	@media screen and (max-width: 480px) {
	#inner {
		width: calc(100% - 80px);
	}
	}
</style>
