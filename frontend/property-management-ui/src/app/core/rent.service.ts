import { Injectable } from '@angular/core';
import { HttpClient } from '@angular/common/http';
import { environment } from '../../../src/environments/environment';
import { Observable } from 'rxjs';

@Injectable({
  providedIn: 'root'
})
export class RentService {
  private api = environment.apiUrl + '/rent';

  constructor(private http: HttpClient) {}

  calculateRent(): Observable<any> {
    return this.http.get(`${this.api}/calculate`);
  }
}
