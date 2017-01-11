module.exports = function(grunt) {
  // Project configuration.
  grunt.initConfig({
    pkg: grunt.file.readJSON('package.json'),
    
    sass: {
      dist: {
        files: {
          'public/assets/css/main.css': 'build/scss/main.scss'
        }
      }
    },

    uglify: {
      options: {
        banner: '/*! <%= pkg.name %> <%= grunt.template.today("yyyy-mm-dd") %> */\n',
        sourceMap: true
      },
      build: {
        src: ['bower_components/jquery/dist/jquery.js',
              'bower_components/hammerjs/hammer.js',
              'bower_components/slick-carousel/slick/slick.js',
              'bower_components/dense/src/dense.js',        
              'bower_components/jquery-unveil/jquery.unveil.min.js',        
              'bower_components/datatables.net/js/jquery.dataTables.js',
              'build/js/*.js'
        ],
        dest: 'public/assets/js/main.js'
      }
    },

    copy: {
  	  images: {
  		  expand: true,
        flatten: true,
  		  src: [
          'build/img/*'
  		  ], 
  		  dest: 'public/assets/img'
  	  },
  	  fonts: {
  		  expand: true,
        flatten: true,
  		  src: [
          'bower_components/font-awesome/fonts/*'
  		  ], 
  		  dest: 'public/assets/fonts'
  	  }
	  },

    postcss: {
        options: {
            map: true,
            processors: [
                require('autoprefixer')({
                    browsers: ['last 2 versions']
                })
            ]
        },
        dist: {
            src: 'public/assets/css/*.css'
        }
    },    
    
    'ftp-deploy': {
      build: {
        auth: {
          host: '****',
          port: 21,
          authKey: 'key1'
        },
        src: '.',
        dest: '.',
        exclusions: ['.git', '.sass-cache', 'bower_components', 'build', 'node_modules', '.api_cache']
      }
    },
    
    watch: {
      scripts: {
        files: ['build/js/**'],
        tasks: ['uglify'],
        options: {
  	      event: ['deleted','changed'],	// Compatible with Transmit Upload
  		    livereload: true,
  	    }
      },
      css: {
        files: ['build/scss/**'],
        tasks: ['sass', 'copy'],
        options: {
  	      event: ['deleted','changed'],	// Compatible with Transmit Upload
  		    livereload: true,
  	    }
      }
    }
  });

  // Load the plugin that provides the "uglify" task.
  grunt.loadNpmTasks('grunt-contrib-uglify');
  grunt.loadNpmTasks('grunt-contrib-watch');
  grunt.loadNpmTasks('grunt-contrib-copy');  
  grunt.loadNpmTasks('grunt-contrib-sass');
  grunt.loadNpmTasks('grunt-postcss');
  grunt.loadNpmTasks('grunt-ftp-deploy');
  
  
  // Default task(s).
  grunt.registerTask('default', ['uglify', 'sass', 'copy', 'postcss:dist']);


};


