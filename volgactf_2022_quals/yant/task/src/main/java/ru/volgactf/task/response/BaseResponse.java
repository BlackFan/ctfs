package ru.volgactf.task.response;

public class BaseResponse {
    private String error;
    private String result;

    public String getError() {
        return error;
    }

    public void setError(String error) {
        this.error = error;
    }

    public String getResult() {
        return result;
    }

    public void setResult(String result) {
        this.result = result;
    }
}