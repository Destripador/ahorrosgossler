import Vue from 'vue'
import App from './settings/Settings'
import AppGlobal from './mixins/AppGlobal'

/**
 * @param fn
 */
function ready(fn) {
	if (document.readyState !== 'loading') {
		fn()
	} else {
		document.addEventListener('DOMContentLoaded', fn)
	}
}

Vue.mixin(AppGlobal)

ready(() => {
	// eslint-disable-next-line
    new Vue({
		el: '#ahorrosgossler',
		render: h => h(App),
	})

})