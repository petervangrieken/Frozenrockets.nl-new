module.exports = function(grunt) {

    require('load-grunt-tasks')(grunt);

    grunt.initConfig({
        pkg: grunt.file.readJSON('package.json'),

        sass: {
            // dist: {
            //     options: {
            //         style: 'nested'
            //     },
            //     files: {
            //         'src/css/style.css': 'src/css/sass/style.scss'
            //     }
            // },
            build: {
                options: {
                    outputStyle: 'compressed',
                    sourcemap: 'none'
                },
                files: {
                    'public/css/style-2016.min.css': 'src/css/sass/style.scss',
                    'public/css/full-2016.min.css': 'src/css/sass/full.scss'
                }
            }
        },
        autoprefixer: {
            options: {
                browsers: ['IE > 9', '> 1% in NL', 'last 2 versions']
            },
            dist: {
                src: 'public/css/style-2016.min.css'
            }
        },
        imagemin: {
            dynamic: {
                options: {
                    optimizationLevel: 5
                },
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
                files: [{
                    expand: true,
                    cwd: './src',
                    src: ['index.html', 'team.html', 'chat.html', 'actie.html', 'english.html', 'over-frozen-rockets.html', 'contact.html', 'accessibility/accessibility-101.html', 'accessibility/alfabet-van-accessibility.html', 'articles/frozen-rockets-gaat-de-wereld-verbeteren.html'],
                    dest: './public',
                    ext: '.html'
                }]
            }
        },
        watch: {
            css: {
                files: ['**/*.scss'],
                tasks: ['sass:build', 'autoprefixer']
            },
            html: {
                files: ['src/**/*.html'],
                tasks: ['bake']
            },
            image: {
                files: ['src/**/*.{png,jpg,gif}'],
                tasks: ['imagemin']
            }
        },

        connect: {
            server: {
                options: {
                    port: 9010,
                    hostname: '0.0.0.0',
                    base: './public',
                    open: false
                }
            }
        }

    });

    grunt.registerTask('default', ['imagemin','watch']);
    grunt.registerTask('serve', ['connect:server','default']);
    grunt.registerTask('package', ['bake','sass:build','autoprefixer','imagemin']);
};
