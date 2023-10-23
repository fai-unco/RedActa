import { NgModule } from '@angular/core';
import { BrowserModule } from '@angular/platform-browser';
import { AppRoutingModule } from './app-routing.module';
import { AppComponent } from './app.component';
import { BrowserAnimationsModule } from '@angular/platform-browser/animations';
import { NbThemeModule, NbMenuModule, NbSidebarModule, NbDatepickerModule, NbDialogModule, NbCardModule, NbButton, NbButtonComponent, NbButtonModule } from '@nebular/theme';
import { NbEvaIconsModule } from '@nebular/eva-icons';
import { HttpClientModule, HTTP_INTERCEPTORS} from '@angular/common/http';
import { AuthInterceptor } from './auth/auth.interceptor';

import { PanelModule } from './panel/panel.module';
import { ErrorDialogComponent } from './shared/error-dialog/error-dialog.component';

@NgModule({
  declarations: [
    AppComponent,
    ErrorDialogComponent
  ],
  imports: [
    BrowserModule,
    AppRoutingModule,
    BrowserAnimationsModule,
    HttpClientModule,
    NbThemeModule.forRoot({ name: localStorage.getItem('uiTheme') || 'default' }),
    NbEvaIconsModule,
    PanelModule,
    NbMenuModule.forRoot(),
    NbSidebarModule.forRoot(), //if this is your app.module
    NbDatepickerModule.forRoot(),
    NbDialogModule.forRoot(),
    NbCardModule,
    NbButtonModule
  ],
  providers: [
    { provide: HTTP_INTERCEPTORS, useClass: AuthInterceptor, multi: true } 
  ],
  bootstrap: [AppComponent]
})
export class AppModule { }
