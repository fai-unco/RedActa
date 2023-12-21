import { ChangeDetectionStrategy, Component, OnInit } from '@angular/core';
import { NbMenuItem, NbThemeService } from '@nebular/theme';

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
    /*{
      title: 'Configuración',
      url: 'configuracion',
      icon: 'settings-2-outline'
    }*/
  ];

  darkModeEnabled = localStorage.getItem('uiTheme') == 'dark';

  constructor (private themeService: NbThemeService) { }

  ngOnInit(): void {
  }

  changeTheme(enableDarkMode: boolean){
    let theme = 'default';
    if(enableDarkMode) {
      theme = 'dark'
    }
    this.themeService.changeTheme(theme);
    localStorage.setItem('uiTheme', theme);
    this.darkModeEnabled = enableDarkMode;
  }


}
