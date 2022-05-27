package ru.volgactf.task.response;

public class LoginResponse extends BaseResponse {
    private String userID;

    public String getUserID() {
        return userID;
    }

    public void setUserID(String userID) {
        this.userID = userID;
    }
}
