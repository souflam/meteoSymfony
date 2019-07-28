import { Component, OnInit, OnDestroy } from '@angular/core';
import { Apollo, Subscription } from 'apollo-angular';
import gql from 'graphql-tag';

@Component({
  selector: 'app-root',
  templateUrl: './app.component.html',
  styleUrls: ['./app.component.css']
})
export class AppComponent implements OnInit{
  rates: any[];
  loading = true;
  error: any;

  constructor(private apollo: Apollo) {}

  ngOnInit() {
    this.apollo
      .watchQuery({
        query: gql`
          {
            meteo(ville: "rabat")
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
        console.log(result);
        //this.rates = result.data && result.data.rates;
        this.loading = result.loading;
        //this.error = result.error;
      });
  }
}
