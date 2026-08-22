import paramiko
import sys

HOSTNAME = '145.79.14.233'
PORT = 65002
USERNAME = 'u674511048'
PASSWORD = '!FarizAhmad123456'
APP_DIR = 'domains/farizahmad.com/public_html/app-reses'

def update_env():
    print("Mencoba terhubung ke server...")
    client = paramiko.SSHClient()
    client.set_missing_host_key_policy(paramiko.AutoAddPolicy())
    
    try:
        client.connect(HOSTNAME, PORT, USERNAME, PASSWORD)
        print("Berhasil terhubung ke SSH!\n")
        
        commands = [
            f'cd {APP_DIR}',
            'sed -i "/EARSIP_API_/d" .env',
            'echo \'EARSIP_API_URL="https://e-arsip.farizahmad.com/api/reses"\' >> .env',
            'echo \'EARSIP_API_TOKEN="1|Uq7Y6HBaPcZH5j16a7EElPUGiyd1UXRBdn7nYp1J82faba74"\' >> .env',
            'php artisan config:clear',
            'php artisan cache:clear'
        ]
        
        command_string = ' && '.join(commands)
        print("Menjalankan pembaruan .env di server live...")
        stdin, stdout, stderr = client.exec_command(command_string)
        
        print("--- OUTPUT SERVER ---")
        print(stdout.read().decode('utf-8'))
        
        err = stderr.read().decode('utf-8')
        if err:
            print("--- ERROR ---")
            print(err)
            
        print("\nSelesai memperbarui .env!")
    except Exception as e:
        print(f"Error: {e}")
    finally:
        client.close()

if __name__ == '__main__':
    update_env()
