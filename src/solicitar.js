import Vue from 'vue'
import App from './views/Solicitar'
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
		el: '#content',
		render: h => h(App),
	})

})