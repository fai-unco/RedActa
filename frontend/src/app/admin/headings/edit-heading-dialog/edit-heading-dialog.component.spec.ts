import { ComponentFixture, TestBed } from '@angular/core/testing';

import { EditHeadingDialogComponent } from './edit-heading-dialog.component';

describe('EditHeadingDialogComponent', () => {
  let component: EditHeadingDialogComponent;
  let fixture: ComponentFixture<EditHeadingDialogComponent>;

  beforeEach(async () => {
    await TestBed.configureTestingModule({
      declarations: [ EditHeadingDialogComponent ]
    })
    .compileComponents();
  });

  beforeEach(() => {
    fixture = TestBed.createComponent(EditHeadingDialogComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
