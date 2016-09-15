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
  
  // Default task(s).
  grunt.registerTask('default', ['uglify', 'sass', 'copy']);

};

