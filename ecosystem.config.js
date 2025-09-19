module.exports = {
  apps: [
    {
      name: 'nipoagro',
      script: 'php',
      args: 'spark serve --host=0.0.0.0 --port=8000',
      cwd: '/home8/johsport/nipoagro.com',
      instances: 1,
      autorestart: true,
      watch: false,
      max_memory_restart: '1G',
      env: {
        CI_ENVIRONMENT: 'production'
      },
      error_file: './logs/err.log',
      out_file: './logs/out.log',
      log_file: './logs/combined.log',
      time: true
    }
  ]
};
