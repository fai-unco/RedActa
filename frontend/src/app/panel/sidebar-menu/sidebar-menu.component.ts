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
    }
  ];

  constructor (private themeService: NbThemeService) { }

  ngOnInit(): void {
  }

  changeTheme(enableDarkMode: boolean){
    if(enableDarkMode) {
      this.themeService.changeTheme('dark');
    } else {
      this.themeService.changeTheme('default');
    }
  }


}
