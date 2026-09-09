import { Component, OnInit } from '@angular/core';
import { ActivatedRoute, Router } from '@angular/router';
import { finalize } from 'rxjs';
import { ApiConnectionService } from 'src/app/api-connection.service';

@Component({
  selector: 'app-signup',
  templateUrl: './signup.component.html',
  styleUrls: ['./signup.component.scss']
})
export class SignupComponent implements OnInit {

  loading = true;
  errorMsg = '';
  successMsg = '';
  invitationToken?: string;
  invitationEmail: string = '';

  constructor(
    private route: ActivatedRoute,
    private api: ApiConnectionService,
    private router: Router
  ) { }

  ngOnInit(): void {
    this.invitationToken = this.route.snapshot.queryParamMap.get('token') ?? undefined;
    if (!this.invitationToken) {
      this.loading = false;
      this.errorMsg = 'Token no proporcionado';
      return;
    }

    this.api.get(`validate_signup_invitation?token=${this.invitationToken}`)
      .pipe(finalize(() => { this.loading = false; }))
      .subscribe({
        next: (res: any) => {
          if (!res.data.isValid) {
            this.errorMsg = 'Invitación inválida o expirada';
            return;
          }
          this.invitationEmail = res.data.invitation.email;
        },
        error: () => {
          this.errorMsg = 'Error consultando la invitación';
        }
      });
  }

  // Recibe el evento (saved) emitido por EditUserDialog; el propio dialog ya envía/guarda los datos.
  onSaved(saved: boolean) {
    this.errorMsg = '';
    this.successMsg = '';
    this.loading = false;

    if (!saved) {
      this.errorMsg = 'No se recibió respuesta del registro';
      return;
    }

    // Si el diálogo devolvió un objeto de usuario (o cualquier señal de éxito) mostramos mensaje y redirigimos.
    this.successMsg = 'Registro completado correctamente! Redireccionando al login...';
    setTimeout(() => {
      this.router.navigate(['/login']);
    }, 5000);
  }

}
