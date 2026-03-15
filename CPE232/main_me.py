import pandas as pd
import numpy as np
from sklearn.ensemble import RandomForestRegressor
from sklearn.multioutput import MultiOutputRegressor
from sklearn.model_selection import train_test_split
from sklearn.metrics import mean_squared_error, mean_absolute_error, r2_score

def load_and_preprocess_data():
    # load datasets
    spotify_df = pd.read_csv("spotify.csv")
    temp_df = pd.read_csv("temp.csv")
    rain_df = pd.read_csv("rain.csv")

    # merge temperature and rainfall data
    environment = pd.merge(temp_df, rain_df, on="date", how="inner")

    # clean spotify data
    columns_to_drop = [
        "spotify_id",
        "daily_movement",
        "weekly_movement",
        "duration_ms",
        "album_release_date",
        "key",
        "mode",
        "daily_rank",
        "time_signature",
        "album_name",
        "artists",
        "popularity",
    ]
    spotify_df = spotify_df.drop(columns=columns_to_drop)

    # filter for thailand data
    spotify_TH = spotify_df[spotify_df["country"] == "TH"]

    # format dates
    spotify_TH["snapshot_date"] = pd.to_datetime(
        spotify_TH["snapshot_date"], errors="coerce"
    ).dt.strftime("%d/%m/%Y")
    spotify_TH["snapshot_date"] = spotify_TH["snapshot_date"].fillna("14/10/2024")

    # merge with environmental data
    train = spotify_TH.merge(environment, left_on="snapshot_date", right_on="date")

    # drop unnecessary columns
    train = train.drop(labels=["snapshot_date", "date", "country"], axis=1)

    # convert boolean to integer
    train["is_explicit"] = train["is_explicit"].astype(int)

    # prepare test data
    test = (
        spotify_TH.drop(labels=["snapshot_date", "country"], axis=1)
        .sort_values(by="name")
        .drop_duplicates(subset="name")
    )

    return train, test


def evaluate_model(model, X_test, y_test):
    # make predictions
    y_pred = model.predict(X_test)

    # calculate metrics for temperature
    temp_mse = mean_squared_error(y_test["average_temp"], y_pred[:, 0])
    temp_rmse = np.sqrt(mean_squared_error(y_test["average_temp"], y_pred[:, 0]))
    temp_mae = mean_absolute_error(y_test["average_temp"], y_pred[:, 0])
    temp_r2 = r2_score(y_test["average_temp"], y_pred[:, 0])

    # calculate metrics for rainfall
    rain_mse = mean_squared_error(y_test["rain"], y_pred[:, 1])
    rain_rmse = np.sqrt(mean_squared_error(y_test["rain"], y_pred[:, 1]))
    rain_mae = mean_absolute_error(y_test["rain"], y_pred[:, 1])
    rain_r2 = r2_score(y_test["rain"], y_pred[:, 1])

    # print metrics
    print(f"Temperature: MSE={temp_mse:.4f}, RMSE={temp_rmse:.4f}, MAE={temp_mae:.4f}, R²={temp_r2:.4f}")
    print(f"Rainfall: MSE={rain_mse:.4f}, RMSE={rain_rmse:.4f}, MAE={rain_mae:.4f}, R²={rain_r2:.4f}")

    return y_pred


def report_feature_importance(model, feature_names):
    # get feature importances for each target
    target_names = ["Temperature", "Rainfall"]

    for i, estimator in enumerate(model.estimators_):
        # get and sort importances
        importances = estimator.feature_importances_
        indices = np.argsort(importances)[::-1]

        # print top features
        print(f"\nTop features for {target_names[i]}:")
        for j in range(len(feature_names)):
            print(f"  {feature_names[indices[j]]}: {importances[indices[j]]:.4f}")


def generate_prediction_csv(test_data, model, filename="song_weather_predictions.csv"):
    # get features without name column
    X_test = test_data.drop(["name"], axis=1)

    # predict temperature and rainfall
    predictions = model.predict(X_test)

    # create results dataframe
    results_df = pd.DataFrame(
        {
            "name": test_data["name"],
            "avg_temp": np.round(predictions[:, 0]).astype(int),
            "total_rain": np.round(predictions[:, 1]).astype(int),
        }
    )

    # save to csv
    results_df.to_csv(filename, index=False)
    print(f"Predictions saved to {filename}")

    return results_df


def main():
    # load data
    train_data, test_data = load_and_preprocess_data()

    # prepare features and targets
    X = train_data.drop(["average_temp", "rain", "name"], axis=1)
    y = train_data[["average_temp", "rain"]]

    # get feature names for importance reporting
    feature_names = X.columns.tolist()

    # split data
    X_train, X_test, y_train, y_test = train_test_split(
        X, y, test_size=0.2, random_state=42
    )

    # train model
    model = MultiOutputRegressor(
        RandomForestRegressor(random_state=42)
    )
    model.fit(X_train, y_train)

    # report feature importance
    report_feature_importance(model, feature_names)

    # evaluate model
    evaluate_model(model, X_test, y_test)

    # generate predictions csv
    generate_prediction_csv(test_data, model)


if __name__ == "__main__":
    main()
