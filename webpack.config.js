path = require('path');
module.exports = {
	entry: {
		'scripts': "./assets/public/js/scripts.js",
	},
	output: {
   		path: path.resolve( __dirname, './' ),
		filename: '[name].js',
	},
	module: {
    	rules: [
    		{
				test: /\.(js|jsx)$/,
				use: { loader: "babel-loader" },
				exclude: /(node_modules|bower_components)/
			}
    	]
  	}
};
