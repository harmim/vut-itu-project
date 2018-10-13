module.exports = function (grunt) {
	require("load-grunt-tasks")(grunt);


	function rename(dest, src) {
		const countKey = "production." + dest;
		let count = grunt.config.get(countKey);
		if (!count) {
			count = Date.now();
		}
		grunt.config.set(countKey, ++count);

		return dest + count + "_" + src;
	}


	grunt.initConfig({
		pkg: grunt.file.readJSON("package.json"),
		banner: "/* <%= pkg.author.name %>, <%= pkg.author.email %> - <%= pkg.name %> -" +
		" <%= grunt.template.today('yyyy-mm-dd') %> */",

		nodeDir: "node_modules",
		jsDir: "www/js",
		cssDir: "www/css",
		fontsDir: "<%= cssDir %>/webfonts",

		files: {
			js: [
				"<%= nodeDir %>/jquery/dist/jquery.js",
				"<%= nodeDir %>/popper.js/dist/umd/popper.js",
				"<%= nodeDir %>/bootstrap/dist/js/bootstrap.js",
				"<%= nodeDir %>/jquery-ui-dist/jquery-ui.js",
				"<%= jsDir %>/main.jss"
			],
			css: [
				"<%= nodeDir %>/bootstrap/dist/css/bootstrap.css",
				"<%= nodeDir %>/@fortawesome/fontawesome-free/css/all.css",
				"<%= cssDir %>/main.css",
			],
			fonts: [
				"<%= nodeDir %>/@fortawesome/fontawesome-free/webfonts/*"
			]
		},

		clean: {
			js: [
				"<%= jsDir %>/development",
				"<%= jsDir %>/production"
			],
			css: [
				"<%= cssDir %>/development",
				"<%= cssDir %>/production"
			],
			fonts: [
				"<%= fontsDir %>"
			],
			jsDevelopment: [
				"<%= jsDir %>/development"
			],
			cssDevelopment: [
				"<%= cssDir %>/development"
			]
		},

		copy: {
			js: {
				files: [
					{
						src: "<%= files.js %>",
						dest: "<%= jsDir %>/development/",
						expand: true,
						flatten: true,
						rename: rename
					}
				]
			},
			css: {
				files: [
					{
						src: "<%= files.css %>",
						dest: "<%= cssDir %>/development/",
						expand: true,
						flatten: true,
						rename: rename
					}
				]
			},
			fonts: {
				files: [
					{src: "<%= files.fonts %>", dest: "<%= fontsDir %>", expand: true, flatten: true}
				]
			}
		},

		uglify: {
			options: {
				banner: "<%= banner %>\n",
				footer: "\n<%= banner %>\n",
				output: {
					comments: false
				}
			},
			default: {
				files: {
					"<%= jsDir %>/production/production_<%= grunt.template.today('lssMlH') %>.js": [
						"<%= jsDir %>/development/*.js"
					]
				}
			}
		},

		cssmin: {
			options: {
				keepSpecialComments: 0
			},
			default: {
				files: {
					"<%= cssDir %>/production/production_<%= grunt.template.today('lssMlH') %>.css": [
						"<%= cssDir %>/development/*.css"
					]
				}
			}
		}
	});


	grunt.registerTask("css-development", ["clean:css", "clean:fonts", "copy:css", "copy:fonts"]);
	grunt.registerTask("css-production", [
		"clean:css", "clean:fonts", "copy:css", "copy:fonts", "cssmin:default", "clean:cssDevelopment"
	]);

	grunt.registerTask("scripts-development", ["clean:js", "copy:js"]);
	grunt.registerTask("scripts-production", ["clean:js", "copy:js", "uglify:default", "clean:jsDevelopment"]);

	grunt.registerTask("development", ["css-development", "scripts-development"]);
	grunt.registerTask("production", ["css-production", "scripts-production"]);

	grunt.registerTask("default", ["development"]);
};
