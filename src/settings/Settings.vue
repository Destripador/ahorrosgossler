<template>
    <div v-if="loading">
		<div class="center-screen" style="background-color: #fff;">
            <NcLoadingIcon :size="64" appearance="dark" name="Loading on light background" />
        </div>
	</div>
	<div v-else>
        <div class="container">
            <div style="display: flex; gap: 12px; flex: 1">
                <div>
                    <h2 class="board-title">
                        <Cash :size="20" decorative class="icon" style="margin-top: 4px;" />
                        <span>Ahorro Gossler</span>
                    </h2>
                </div>                  
            </div>
        </div>

        <div class="container" name="" v-if="UsersWithAhorro.length > 0">
            <div style="display: flex; gap: 12px; flex: 1">
                <div>
                    <h2 class="titles">
                        <span>Usuarios con cuenta de ahorro</span>
                    </h2>
                </div>
                <div style="margin-left: auto; margin-right: 0;margin-top: 4px;">
                    <NcButton @click="$refs.file.click()" alignment="end" type="primary">
                        <template #icon>
                            <DatabaseImport :size="20" />
                        </template>
                        Importar
                    </NcButton>
                    <input type="file" ref="file" style="display: none" @change="importar()" />
                </div>                    
            </div>
			<table class="grid">
                <tr>
                    <th class="header__cell header__cell--avatar">&nbsp;</th>
                    <th>{{ t('ahorrosgossler', 'Nombre') }}</th>
                    <th>{{ t('ahorrosgossler', 'Cantidad') }}</th>
                    <th>{{ t('ahorrosgossler', 'Permiso') }}</th>
                    <th>{{ t('ahorrosgossler', 'Estado') }}</th>
                    <th>{{ t('ahorrosgossler', 'Opciones') }}</th>
                </tr>
                <tr v-for="(item, index) in UsersWithAhorro">
                    <td class="row__cell row__cell--avatar">
                        <NcAvatar :user="item.uid" :display-name="item.displayname" :showUserStatusCompact="false" :showUserStatus="false"/>
                    </td>

                    <td>
                        {{ item.displayname }}
                    </td>

                    <td>
                        <NcTextField :disabled="index !== indiceRegistroSeleccionado" :id="'TextField-cantidad-' + item.uid" :value.sync="nuevacantidad[index] = item.cantidad" />
                    </td>

                    <!---- Tipos de permisos ---->
                    <td>
                        <NcTextField 
                            v-if="index !== indiceRegistroSeleccionado"
                            :value="obtenerLabelPorValuePermision(item.id_permision)" 
                            :disabled="true"/>
                        
                        <NcSelect v-if="index == indiceRegistroSeleccionado"
                                labelOutside
                                v-model="listaItems.id_permision"
                                v-bind="obtenerLabelPorValuePermision(nuevopermision)"
                                :options="opcionespermision" 
                                @option:selected="cambiarOpcionpermision(index)"/>
                    </td>

                    <!---- Tipos de estado del usuario ---->
                    <td>
                        <NcTextField 
                            v-if="index !== indiceRegistroSeleccionado"
                            :value="obtenerLabelPorValue(item.state)" 
                            :disabled="true"/>
                        
                        <NcSelect v-if="index == indiceRegistroSeleccionado"
                                labelOutside
                                v-model="listaItems.state"
                                v-bind="obtenerLabelPorValue(nuevostate)"
                                :options="opciones" 
                                @option:selected="cambiarOpcionstate(index)"/>
                    </td>
                    
                    <td> 
                        <NcActions>
                            <NcActionButton @click="edit(index, item.cantidad, item.id_permision, item.state)" v-if="index !== indiceRegistroSeleccionado">
                                <template #icon>
                                    <Cog :size="20" />
                                </template>
                                Agregar
                            </NcActionButton>
                            <NcActionButton @click="save(index)" v-if="index == indiceRegistroSeleccionado">
                                <template #icon>
                                    <Check :size="20" />
                                </template>
                                Agregar
                            </NcActionButton>
                        </NcActions>
                    </td>
                </tr>
            </table>               
		</div>

        <br>

        <div class="container" v-if="Users.length > 0">
            <div style="display: flex; gap: 12px; flex: 1">
                <div>
                    <h2 class="titles">
                        <span>Usuarios sin cuenta de ahorro</span>
                    </h2>
                </div>                   
            </div>

            <table class="grid">
                <tr>
                    <th class="header__cell header__cell--avatar">&nbsp;</th>
                    <th>{{ t('ahorrosgossler', 'Nombre') }}</th>
                    <th>{{ t('ahorrosgossler', 'UID') }}</th>
                    <th>{{ t('ahorrosgossler', 'Opciones') }}</th>
                </tr>
                <tr v-for="(item, index) in Users">
                    <td>
                        <NcAvatar :size="44" :user="item.uid" :display-name="item.uid" :showUserStatusCompact="false" :showUserStatus="false"/>
                    </td>

                    <td>
                        {{ item.displayname }}
                    </td>

                    <td>
                        {{ item.uid }}
                    </td>
                    
                    <td>
                        <NcActions>
                            <NcActionButton @click="createUser(index)">
                                <template #icon>
                                    <WalletPlus :size="20" />
                                </template>
                                Agregar
                            </NcActionButton>
                        </NcActions>
                    </td>
                </tr>
            </table>     
        </div>
    </div>
</template>

<script>
//iconos
import Cash from 'vue-material-design-icons/Cash'
import WalletPlus from 'vue-material-design-icons/WalletPlus'
import Cog from 'vue-material-design-icons/Cog'
import Check from 'vue-material-design-icons/Check'
import DatabaseImport from 'vue-material-design-icons/DatabaseImport'

//CSS
import '@nextcloud/dialogs/styles/toast.scss'

//imports
import {NcActions, NcActionButton, NcLoadingIcon, NcAvatar, NcTextField, NcSelect, NcButton} from '@nextcloud/vue'
import { generateUrl } from '@nextcloud/router'
import { showError, showSuccess } from '@nextcloud/dialogs'
import axios from '@nextcloud/axios'

export default {
	name: 'App',
	components: { NcActions,  NcActionButton, NcLoadingIcon, NcAvatar, WalletPlus, Cog, Check, NcTextField, NcSelect, NcButton, DatabaseImport, Cash },
    data() {
		return {
            nuevacantidad: [],
            nuevostate: '',
            nuevopermision: '',
            loading: true,
            Users: [],
            UsersWithAhorro: [],
            indiceRegistroSeleccionado: null,
            listaItems: [
                { cantidad: '', id_permision: '', state: '' },
            ],
            opciones: [
                { value: '0', label: 'Deshabilitado' },
                { value: '1', label: 'Habiltado' },
                { value: '2', label: 'Suspendido' }
            ],
            opcionespermision: [
                { value: '0', label: 'Usuario' },
                { value: '1', label: 'Gerente' },
                { value: '2', label: 'Socio' },
                { value: '3', label: 'Administrador' },
                { value: '4', label: 'SUDO' }
            ],
		}
	},

    async mounted() {
       this.getall();
    },

    methods:{
        async getall(){
            try {
                this.loading = true
                let array1 = []
                let array2 = []
                let map = {}
                this.UsersWithAhorro = []
                this.Users = []

                const response = await axios.get(generateUrl('/apps/ahorrosgossler/getUsersAhorros'))
                .then(
                    (response) => {
                        array1 = response.data.Users
                        array2 = response.data.UsersWithAhorro
                        for (let i = 0; i < array2.length; i++) {
                            map[array2[i].id_user] = true;
                            this.UsersWithAhorro.push(array2[i])
                        }

                        // Recorrer el segundo array y verificar si sus elementos existen en el objeto
                        for (let i = 0; i < array1.length; i++) {
                            if (!map[array1[i].uid]) {
                                this.Users.push(array1[i])
                            }
                        }
                        
                        this.loading = false
                    },
                    (err) => {
                        showError(t('ahorrosgossler', 'Se ha producido un error, reporte al administrador'))
                        
                    },
                )
            }catch (e) {
                console.error(e)
                showError(t('ahorrosgossler', 'Could not fetch list'))
            }
        },

        edit(index, cantidad, id_permision, state){
            this.indiceRegistroSeleccionado = index;
            this.listaItems['cantidad'] = cantidad
            this.listaItems['id_permision'] = this.obtenerLabelPorValuePermision(id_permision)
            this.listaItems['state'] = this.obtenerLabelPorValue(state)
        },

        async save(index){
            this.listaItems['cantidad'] = this.nuevacantidad[index]
          
            await axios.post(
				generateUrl('/apps/ahorrosgossler/updateUser'),
				{
                    'id': this.UsersWithAhorro[index].id,
                    'id_permision': this.valuexlabelpermision(this.listaItems['id_permision']),
                    'state':this.valuexlabel(this.listaItems['state']),
                    'cantidad': this.listaItems['cantidad'],
                },
			).then(() => {
                this.indiceRegistroSeleccionado = null;
                this.getall();         
			})
        },

        createUser(index){
            axios.post(
				generateUrl('/apps/ahorrosgossler/addUser'),
				{
                    'id_user': this.Users[index].uid,
                    'id_permision': '0',
                    'state': '0',
                    'cantidad': '0',
                },
			).then(() => {
                this.getall();       
			})
        },

        obtenerLabelPorValue(value) {
            for (let i = 0; i < this.opciones.length; i++) {
                // Si encuentra la opción con el value deseado, devuelve su label
                if (this.opciones[i].value === value) {
                    return this.opciones[i].label;
                }
            }
            // Si no se encuentra ninguna opción con el value dado, devuelve null o un valor predeterminado según tus necesidades
            return null;
        },

        valuexlabel(value) {
            for (let i = 0; i < this.opciones.length; i++) {
                // Si encuentra la opción con el value deseado, devuelve su label
                if (this.opciones[i].label === value) {
                    return this.opciones[i].value;
                }
            }
            // Si no se encuentra ninguna opción con el value dado, devuelve null o un valor predeterminado según tus necesidades
            return null;
        },

        obtenerLabelPorValuePermision(value) {
            for (let i = 0; i < this.opcionespermision.length; i++) {
                // Si encuentra la opción con el value deseado, devuelve su label
                if (this.opcionespermision[i].value === value) {
                    return this.opcionespermision[i].label;
                }
            }
            // Si no se encuentra ninguna opción con el value dado, devuelve null o un valor predeterminado según tus necesidades
            return null;
        },

        valuexlabelpermision(value) {
            for (let i = 0; i < this.opcionespermision.length; i++) {
                // Si encuentra la opción con el value deseado, devuelve su label
                if (this.opcionespermision[i].label === value) {
                    return this.opcionespermision[i].value;
                }
            }
            // Si no se encuentra ninguna opción con el value dado, devuelve null o un valor predeterminado según tus necesidades
            return null;
        },

        cambiarOpcionstate(index){
            console.log("cambio estado")
            console.log(this.nuevostate = this.listaItems.state)
            this.listaItems['state'] = this.nuevostate.label
        },

        cambiarOpcionpermision(index){
            console.log("cambio permision")
            console.log(this.nuevopermision = this.listaItems.id_permision)
            this.listaItems['id_permision'] = this.nuevopermision.label
        },

        importar(){
            console.log(this.$refs.file.files)
            
           /*<input type="file" ref="file" style="display: none" @change="importar()" />
           <input
                type="file"
                style="display: none"
                ref="fileInput"
                accept="image/*"
                @change="onFilePicked"/>
           
           */
        }
    }
}
</script>

<style>
.center-screen {
  display: flex;
  justify-content: center;
  align-items: center;
  text-align: center;
  min-height: 100vh;
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
.titles {
	margin-right: 10px;
	margin-top: 14px;
	font-size: 17px;
	display: flex;
	align-items: center;
	.icon {
		margin-right: 8px;
	}
}
.container {
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
</style>