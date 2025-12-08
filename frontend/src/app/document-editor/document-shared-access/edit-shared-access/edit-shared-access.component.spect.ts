import { ComponentFixture, TestBed } from '@angular/core/testing';

import { EditSharedAccessComponent } from './edit-shared-access.component';

describe('EditSharedAccessComponent', () => {
  let component: EditSharedAccessComponent;
  let fixture: ComponentFixture<EditSharedAccessComponent>;
  beforeEach(async () => {
    await TestBed.configureTestingModule({
      declarations: [ EditSharedAccessComponent ]
    })
    .compileComponents();
  });

  beforeEach(() => {
    fixture = TestBed.createComponent(EditSharedAccessComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
