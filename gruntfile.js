module.exports = function(grunt) {

    require('load-grunt-tasks')(grunt);

    grunt.initConfig({
        pkg: grunt.file.readJSON('package.json'),

        sass: {
            dist: {
                options: {
                    style: 'nested'
                },
                files: {
                    'src/css/style.css': 'src/css/sass/style.scss'
                }
            },
            build: {
                options: {
                    style: 'compressed'
                },
                files: {
                    'public/css/style.min.css': 'src/css/sass/style.scss'
                }
            }
        },
        imagemin: {
            dynamic: {
              files: [{
                expand: true,
                cwd: 'src/images',
                src: ['**/*.{png,jpg,gif}'],
                dest: 'public/images'
              }]
            }
        },
        bake: {
            build: {
                options: {
                    content: "src/pagesettings.json"
                },
                files: [{
                    expand: true,
                    cwd: './src',
                    src: ['*.html', 'articles/*.html'],
                    dest: './public',
                    ext: '.html'
                }]
            }
        },
        validation: {
            options: {
                stoponerror: false,
                remoteFiles: ['public/*.html'], 
            }
        },
        stylestats: {
            src: ['public/css/style.min.css']
        },
        watch: {
            css: {
                files: ['**/*.scss'],
                tasks: ['sass:dist', 'sass:build', 'stylestats']
            },
            html: {
                files: ['src/**/*.html'],
                tasks: ['bake', 'validation']
            },
            image: {
                files: ['src/**/*.{png,jpg,gif}'],
                tasks: ['imagemin']
            }
        }

    });

    grunt.registerTask('default', ['imagemin','watch']);
    grunt.registerTask('validate', ['validation']);
};