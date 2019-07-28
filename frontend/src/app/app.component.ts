import { Component, OnInit, OnDestroy } from '@angular/core';
import { Apollo, Subscription } from 'apollo-angular';
import gql from 'graphql-tag';

@Component({
  selector: 'app-root',
  templateUrl: './app.component.html',
  styleUrls: ['./app.component.css']
})
export class AppComponent implements OnInit{
  meteo: any;
  loading = true;
  error: any;

  constructor(private apollo: Apollo) {}
  ville = 'Rabat';
  ngOnInit() {
    this.getMeteo(this.ville);
  }

  getMeteo(ville) {
    this.apollo
    .watchQuery({
      query: gql`
        {
          meteo(ville: "${this.ville}")
          {
            textMeteo,
            minTemperature,
            maxTemperature,
            ville
          }
        }
      `,
    })
    .valueChanges.subscribe(result => {
      console.log(result.data);
      this.meteo = result.data;
      this.loading = result.loading;
      //this.error = result.error;
    });
  }
}
