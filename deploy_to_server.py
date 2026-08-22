import paramiko
import sys

# Konfigurasi Server (Harap jaga kerahasiaan file ini karena berisi password!)
HOSTNAME = '145.79.14.233'
PORT = 65002
USERNAME = 'u674511048'
PASSWORD = '!FarizAhmad123456'

# Path ke dalam folder aplikasi di server
APP_DIR = 'domains/farizahmad.com/public_html/app-reses'

def deploy():
    print("Mencoba terhubung ke server...")
    client = paramiko.SSHClient()
    client.set_missing_host_key_policy(paramiko.AutoAddPolicy())
    
    try:
        client.connect(HOSTNAME, PORT, USERNAME, PASSWORD)
        print("Berhasil terhubung ke SSH!\n")
        
        # Kumpulan perintah yang akan dijalankan di server
        commands = [
            f'cd {APP_DIR}',
            'echo "Menarik kode terbaru dari GitHub..."',
            'git pull origin farizahmad.github.io',
            'echo "\nMemperbarui library composer..."',
            'composer install --no-dev --optimize-autoloader',
            'echo "\nMembersihkan cache framework..."',
            'php artisan optimize:clear',
            'echo "\nMenjalankan migrasi database..."',
            'php artisan migrate --force',
            'echo "\nDEPLOYMENT SELESAI!"'
        ]
        
        # Menyatukan semua perintah dan menjalankannya
        command_string = ' && '.join(commands)
        
        print("Menjalankan perintah update di server...\n")
        stdin, stdout, stderr = client.exec_command(command_string)
        
        # Tampilkan output dari server ke layar terminal lokal
        print("--- OUTPUT SERVER ---")
        print(stdout.read().decode('utf-8'))
        
        err = stderr.read().decode('utf-8')
        if err:
            print("--- ERROR DARI SERVER (Jika Ada) ---")
            print(err)
            
    except Exception as e:
        print(f"Gagal Terhubung / Error: {e}")
        sys.exit(1)
    finally:
        client.close()

if __name__ == '__main__':
    deploy()
