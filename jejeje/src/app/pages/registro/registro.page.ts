import { Component, OnInit } from '@angular/core';
import { NavController } from '@ionic/angular';
import { Auth, createUserWithEmailAndPassword } from '@angular/fire/auth';
import { User } from '../../interfaces/index';
import { ApiService } from 'src/app/services/api.service';
import { UserDataService } from 'src/app/services/user-data.service';
import { Router } from '@angular/router';

@Component({
  selector: 'app-registro',
  templateUrl: './registro.page.html',
  styleUrls: ['./registro.page.scss'],
})
export class RegistroPage implements OnInit {
  name: string = '';
  email: string = '';
  password: string = '';

  constructor(
    public router: Router,
    public navCntrl: NavController,
    private auth: Auth,
    private apiService: ApiService,
    private userDataService: UserDataService
  ) {}

  ngOnInit() {}

  async signup() {
    try {
      const userCredential = await createUserWithEmailAndPassword(
        this.auth,
        this.email,
        this.password
      );

      // Guardar datos del usuario en tu base de datos
      const user: User = {
        id_usuario: userCredential.user.uid,
        nombre_usuario: this.name,
        correo_electronico: this.email,
        contrasena: this.password
      };

      // Con esto guardo al usuario
      this.userDataService.setUserData(user.correo_electronico, user.id_usuario);

      // Aqui nomas imprimo los datos y el token
      this.userDataService.printUserData();

      // Guardar datos del usuario en la base de datos
      this.apiService.saveUserData(user).subscribe((response) => {
        console.log('Usuario guardado en la base de datos:', response);
      });

      this.router.navigate(['/tabs']);

      return userCredential;
    } catch (error) {
      console.error('Error al registrar usuario:', error);
      throw error;
    }
  }

  gotoLogin() {
    this.navCntrl.navigateBack('login');
  }
}