package ru.volgactf.task.request;

public class AddNoteRequest extends BaseRequest {
    
    private String text;  

    public String getText() {
        return text;
    }

    public void setText(String text) {
        this.text = text;
    }

}
