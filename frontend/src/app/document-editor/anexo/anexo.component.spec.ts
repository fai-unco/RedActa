import { ComponentFixture, TestBed } from '@angular/core/testing';

import { AnexoComponent } from './anexo.component';

describe('AnexoComponent', () => {
  let component: AnexoComponent;
  let fixture: ComponentFixture<AnexoComponent>;

  beforeEach(async () => {
    await TestBed.configureTestingModule({
      declarations: [ AnexoComponent ]
    })
    .compileComponents();
  });

  beforeEach(() => {
    fixture = TestBed.createComponent(AnexoComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
