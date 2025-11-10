import { ComponentFixture, TestBed } from '@angular/core/testing';

import { IssuerSettingsDialogComponent } from './issuer-settings-dialog.component';

describe('IssuerSettingsDialogComponent', () => {
  let component: IssuerSettingsDialogComponent;
  let fixture: ComponentFixture<IssuerSettingsDialogComponent>;

  beforeEach(async () => {
    await TestBed.configureTestingModule({
      declarations: [ IssuerSettingsDialogComponent ]
    })
    .compileComponents();
  });

  beforeEach(() => {
    fixture = TestBed.createComponent(IssuerSettingsDialogComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
