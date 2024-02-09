import { ComponentFixture, TestBed } from '@angular/core/testing';

import { InitSettingsDialogComponent } from './init-settings-dialog.component';

describe('InitSettingsModalComponent', () => {
  let component: InitSettingsDialogComponent;
  let fixture: ComponentFixture<InitSettingsDialogComponent>;

  beforeEach(async () => {
    await TestBed.configureTestingModule({
      declarations: [ InitSettingsDialogComponent ]
    })
    .compileComponents();
  });

  beforeEach(() => {
    fixture = TestBed.createComponent(InitSettingsDialogComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
