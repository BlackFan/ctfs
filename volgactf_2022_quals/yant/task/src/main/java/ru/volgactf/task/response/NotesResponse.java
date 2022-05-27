package ru.volgactf.task.response;

import java.util.List;
import ru.volgactf.task.model.Note;

public class NotesResponse extends BaseResponse {
    private List<Note> notes;

    public List<Note> getNotes() {
        return notes;
    }

    public void setNotes(List<Note> notes) {
        this.notes = notes;
    }
    
}
