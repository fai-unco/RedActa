import { ComponentFixture, TestBed } from '@angular/core/testing';

import { AddSignupInvitationComponent } from './add-signup-invitation.component';

describe('AddSignupInvitationComponent', () => {
  let component: AddSignupInvitationComponent;
  let fixture: ComponentFixture<AddSignupInvitationComponent>;

  beforeEach(async () => {
    await TestBed.configureTestingModule({
      declarations: [ AddSignupInvitationComponent ]
    })
    .compileComponents();
  });

  beforeEach(() => {
    fixture = TestBed.createComponent(AddSignupInvitationComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
