import { ChangeDetectionStrategy, Component, OnInit } from '@angular/core';
import { NbMenuItem, NbThemeService } from '@nebular/theme';
import { AuthService } from 'src/app/auth/auth-core/auth.service';

@Component({
  selector: 'app-sidebar-menu',
  templateUrl: './sidebar-menu.component.html',
  styleUrls: ['./sidebar-menu.component.scss'],
  changeDetection: ChangeDetectionStrategy.OnPush,
})
export class SidebarMenuComponent implements OnInit {

  items: NbMenuItem[]  = [
    {
      title: 'Crear documento',
      url: 'documentos/editar',
      //expanded: true,
      icon: 'plus-outline'
    },
    {
      title: 'Buscar documento',
      url: 'documentos/buscar',
      icon: 'search-outline'
    },
    {
      title: 'Administrar sellos',
      url: 'sellos',
      icon: 'settings-2-outline'
    }
    
    /*{
      title: 'Configuración',
      url: 'configuracion',
      icon: 'settings-2-outline'
    }*/
  ];

  darkModeEnabled = localStorage.getItem('uiTheme') == 'dark';

  constructor (private themeService: NbThemeService, private authService: AuthService) { }

  ngOnInit(): void {
    if (["local_admin", "super_admin"].includes(this.authService.getCurrentUserRole())) {
      this.items.push({
        title: 'Administración',
        icon: 'settings-2-outline',
        children: [
          {
            title: 'Cuentas',
            link: 'admin/cuentas',
            icon: 'people-outline'
          },
          {
            title: 'Dependencias',
            link: 'admin/dependencias',
            icon: 'home-outline'
          },
          {
            title: 'Membretes',
            link: 'admin/membretes',
            icon: 'home-outline'
          }
        ]
      });
    }
  }

  changeTheme(enableDarkMode: boolean){
    let theme = 'default';
    if(enableDarkMode) {
      theme = 'dark'
    }
    this.themeService.changeTheme(theme);
    localStorage.setItem('uiTheme', theme);
    this.darkModeEnabled = enableDarkMode;
    window.dispatchEvent(new Event('uiTheme'))
  }


}
