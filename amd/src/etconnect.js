export const start = (quizname, fullname, idnumber, proctorid, quizid, username, userid, quizattempts) => {
    window.console.log(`The user name is '${username}' and the id is '${userid}'`);
    if (window.location !== window.parent.location) {
        window.parent.postMessage({
            'msg':              'start',            //Mandatory
            'ExamTitle':        `${quizname}`,      //Optional
            'ExamDetails':      `${quizid}`,        //Optional
            'EnrolmentNumber':  `${userid}`,        //Optional
            'Name':             `${fullname}`,      //Optional
            'StudentId':        `${idnumber}`,      //Optional
            'AttemptNumber':    `${quizattempts}`,  //Optional
            'Proctor':          `${proctorid}`,     //Proctoring group id
        }, "*");
    }
};

export const finish = (first, last) => {
    window.console.log(`The user name is '${first}' and the id is '${last}'`);
    if (window.location !== window.parent.location) {
        window.parent.postMessage({
            'msg': 'stop',
        }, "*");
    }
};
