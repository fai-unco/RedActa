import { NgModule } from '@angular/core';
import { CommonModule } from '@angular/common';
import { ResetPasswordRoutingModule } from './reset-password-routing.module';
import { ReactiveFormsModule } from '@angular/forms';
import { NbCardModule, NbInputModule, NbButtonModule, NbSpinnerModule, NbLayoutModule, NbIconModule, NbFormFieldModule } from '@nebular/theme';
import { ResetPasswordComponent } from './reset-password.component';

@NgModule({
  declarations: [ResetPasswordComponent],
  imports: [
    CommonModule,
    ResetPasswordRoutingModule,
    ReactiveFormsModule,
    NbCardModule, NbInputModule, NbButtonModule, NbSpinnerModule, NbLayoutModule, NbIconModule, NbFormFieldModule
  ]
})
export class ResetPasswordModule {}