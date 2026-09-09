import { Component, Inject, OnDestroy, OnInit } from '@angular/core';
import { NB_WINDOW, NbDialogService, NbMenuService, NbSidebarService} from '@nebular/theme';
import { finalize, Subscription } from 'rxjs';
import { ApiConnectionService } from 'src/app/api-connection.service';
import { AuthService } from 'src/app/auth/auth-core/auth.service';
import { EditUserDialogComponent } from 'src/app/shared/edit-user-dialog/edit-user-dialog.component';
import { ErrorHandlerService } from 'src/app/shared/error-handler/error-handler.service';

@Component({
  selector: 'app-navbar',
  templateUrl: './navbar.component.html',
  styleUrls: ['./navbar.component.scss']
})
export class NavbarComponent implements OnInit, OnDestroy {

  sidebarIsCollapsed = false;
  userMenu = [ 
    {
      title: 'Editar perfil',
    },
    { 
      title: 'Cerrar sesión', 
    } 
  ];
  menuSubscription!: Subscription;
  loading: boolean = false;
 
  constructor(private sidebarService: NbSidebarService, 
    @Inject(NB_WINDOW) private window: any, 
    private nbMenuService: NbMenuService, 
    private authService: AuthService,
    private dialogService: NbDialogService,
    private api: ApiConnectionService,
    private errorHandler: ErrorHandlerService
  ) { }

  ngOnInit() {
    this.menuSubscription = this.nbMenuService.onItemClick()
      .subscribe((event) => {
        if(event.item.title === 'Cerrar sesión'){
          this.authService.logout();
        }
        if (event.item.title === 'Editar perfil') {
          this.loading = true;
          this.dialogService.open(EditUserDialogComponent, {
            context: {
              userId: this.authService.getCurrentUserId(),
              allowRolesSelection: true
            }
          });   
        }
      });
  }

  ngOnDestroy(): void {
    this.menuSubscription.unsubscribe();
  }

  toggleSidebar(){
    if (this.sidebarIsCollapsed){
      this.sidebarService.expand('sidebar')
    } else {
      this.sidebarService.collapse('sidebar')
    }
    this.sidebarIsCollapsed = !this.sidebarIsCollapsed;
  }

  loggedInUsername(): string {
    return this.authService.loggedInUsername();
  }
  
}
