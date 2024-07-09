// SPDX-FileCopyrightText: Luis Angel Alvarado Hernandez <luis.alvarado@crowe.mx>
// SPDX-License-Identifier: AGPL-3.0-or-later
const webpackConfig = require('@nextcloud/webpack-vue-config')

const path = require('path')

webpackConfig.entry = {
	main: path.join(__dirname, 'src', 'main.js'),
	settings: path.join(__dirname, 'src', 'settings.js'),
	sidenavigation: path.join(__dirname, 'src', 'sidenavigation.js'),
	historial: path.join(__dirname, 'src',  'historial.js'),
	solicitar: path.join(__dirname, 'src',  'solicitar.js'),
	panel: path.join(__dirname, 'src',  'panel.js'),
	MostrarDetalle: path.join(__dirname, 'src',  'MostrarDetalle.js')
}

module.exports = webpackConfig
