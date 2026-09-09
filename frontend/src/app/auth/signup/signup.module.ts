import { NgModule } from '@angular/core';
import { CommonModule } from '@angular/common';
import { SignupRoutingModule } from './signup-routing.module';
//import { SignupComponent } from './signup.component';
import { ReactiveFormsModule, FormsModule } from '@angular/forms';
import { NbCardModule, NbInputModule, NbButtonModule, NbSpinnerModule, NbIconModule, NbLayoutModule } from '@nebular/theme';
import { SignupComponent } from './signup.component';
import { SharedModule } from 'src/app/shared/shared.module';

@NgModule({
  declarations: [
    //SignupComponent
  
    SignupComponent
  ],
  imports: [
    CommonModule,
    FormsModule,
    ReactiveFormsModule,
    NbCardModule,
    NbLayoutModule,
    NbInputModule,
    NbButtonModule,
    NbSpinnerModule,
    NbIconModule,
    SignupRoutingModule,
    SharedModule
  ]
})
export class SignupModule {}
