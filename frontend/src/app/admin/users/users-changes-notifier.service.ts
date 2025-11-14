import { Injectable } from '@angular/core';
import { BehaviorSubject } from 'rxjs';

@Injectable({
  providedIn: 'root'
})
export class UsersChangesNotifierService {

  notifier = new BehaviorSubject<number>(0);

  constructor() { }
  
  notify () {
    this.notifier.next(1);
  }

}
