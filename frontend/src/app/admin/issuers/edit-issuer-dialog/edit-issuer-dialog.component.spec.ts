import { ComponentFixture, TestBed } from '@angular/core/testing';

import { EditIssuerDialogComponent } from './edit-issuer-dialog.component';

describe('EditIssuerDialogComponent', () => {
  let component: EditIssuerDialogComponent;
  let fixture: ComponentFixture<EditIssuerDialogComponent>;

  beforeEach(async () => {
    await TestBed.configureTestingModule({
      declarations: [ EditIssuerDialogComponent ]
    })
    .compileComponents();
  });

  beforeEach(() => {
    fixture = TestBed.createComponent(EditIssuerDialogComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
